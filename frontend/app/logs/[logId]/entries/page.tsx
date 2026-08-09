// frontend/app/entries/[logId]/page.tsx
'use client';

import React, { useState, useMemo, useCallback, useRef, useEffect} from 'react';
import { useParams, useRouter } from 'next/navigation';
import { AgGridReact } from 'ag-grid-react';
import {
  ColDef,
  GridReadyEvent,
  CellValueChangedEvent,
  SelectionChangedEvent,
  AllCommunityModule,
  ModuleRegistry,
} from 'ag-grid-community';

import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-quartz.css';

ModuleRegistry.registerModules([AllCommunityModule]);

const API_BASE = process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000';

interface EntryRow {
  id: number;
  log_id: number;
  barang_id: number;
  location: number;
  isi: number;
  tapel: number;
  tinggi: number;
  sisa: number;
  physical_stock: number;
  omega_stock: number;
  difference: number;
  notes: string | null;
  created_at: string;
  updated_at: string;
  barang?: { id: number; kode: string };
}

interface LogSummary {
  id: number;
  recorded_on: string;
}

interface FloatingNavbarProps {
  onBack?: () => void;
  onAdd?: () => void;
  onSave?: () => void;
  onRemove?: () => void;
  canRemove?: boolean;
  saving?: boolean;
  removing?: boolean;
}

interface BarangOption {
  id: number;
  kode: string;
}

function FloatingNavbar({ 
  onBack,
  onAdd,
  onSave,
  onRemove,
  canRemove,
  saving,
  removing,
 }: FloatingNavbarProps) {
  const [visible, setVisible] = useState(true); // visible by default at page top
  const lastScrollY = useRef(0);

  useEffect(() => {
    const handleScroll = () => {
      const currentY = window.scrollY;

      if (currentY < 50) {
        setVisible(true); // always visible near the top
      } else if (currentY > lastScrollY.current) {
        setVisible(false); // scrolling down -> hide
      } else {
        setVisible(true); // scrolling up -> show
      }
      lastScrollY.current = currentY;
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <nav
      className={`fixed top-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-4
        px-5 py-2.5 rounded-full bg-white/80 backdrop-blur-md shadow-lg border border-gray-200
        transition-all duration-300 ease-out
        ${visible ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4 pointer-events-none'}`}
    >
      {onBack && (
        <button
          onClick={onBack}
          className="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors"
        >
          ← Back
        </button>
      )}
      {onSave && (
        <button
          onClick={onSave}
          disabled={saving}
          className="text-sm font-medium px-3 py-1 rounded-full bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 transition-colors"
        >
          {saving ? 'Saving…' : 'Save'}
        </button>
      )}
      {onAdd && (
        <button
          onClick={onAdd}
          className="text-sm font-medium px-3 py-1 rounded-full bg-gray-800 text-white hover:bg-gray-900 transition-colors"
        >
          + Add
        </button>
      )}
      {onRemove && (
        <button
          onClick={onRemove}
          disabled={!canRemove || removing}
          className="text-sm font-medium px-3 py-1 rounded-full bg-red-600 text-white hover:bg-red-700 disabled:opacity-40 transition-colors"
        >
          {removing ? 'Removing…' : 'Remove'}
        </button>
      )}
    </nav>
  );
}

export default function EntriesPage() {
  const router = useRouter();
  const params = useParams<{ logId: string }>();
  const logId = params.logId;
  const gridRef = useRef<AgGridReact<EntryRow>>(null);

  const [rowData, setRowData] = useState<EntryRow[]>([]);
  const [log, setLog] = useState<LogSummary | null>(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [removing, setRemoving] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const [selectedRow, setSelectedRow] = useState<EntryRow | null>(null);

  const [showAddModal, setShowAddModal] = useState(false);
  const [barangOptions, setBarangOptions] = useState<BarangOption[]>([]);
  const [selectedBarangId, setSelectedBarangId] = useState('');
  const [loadingOptions, setLoadingOptions] = useState(false);
  const [addingRow, setAddingRow] = useState(false);

  const fetchEntries = useCallback(() => {
    setLoading(true);
    fetch(`${API_BASE}/api/logs/${logId}/entries`, {
      headers: { Accept: 'application/json' },
    })
      .then((resp) => {
        if (!resp.ok) throw new Error(`Request failed: ${resp.status}`);
        return resp.json();
      })
      .then((json: { log: LogSummary; entries: EntryRow[] }) => {
        setLog(json.log);
        setRowData(json.entries);
      })
      .catch((err) =>
        setError(err instanceof Error ? err.message : 'Failed to load entries')
      )
      .finally(() => setLoading(false));
  }, [logId]);

  const onGridReady = useCallback(
    (gridParams: GridReadyEvent) => {
      fetchEntries();
    },
    [fetchEntries]
  );

 // Recompute the physical_stock cell whenever isi/tapel/tinggi/sisa is edited
  const onCellValueChanged = useCallback((event: CellValueChangedEvent<EntryRow>) => {
    event.api.refreshCells({ rowNodes: [event.node], force: true });
  }, []);

  const onSelectionChanged = useCallback((event: SelectionChangedEvent<EntryRow>) => {
    const rows = event.api.getSelectedRows();
    setSelectedRow(rows.length > 0 ? rows[0] : null);
  }, []);

  const columnDefs = useMemo<ColDef<EntryRow>[]>(
    () => [
      {
        field: 'barang.kode' as any,
        headerName: 'Item',
        flex: 5,
        valueGetter: (p) => p.data?.barang?.kode ?? `#${p.data?.barang_id}`,
      },
      { field: 'location', 
        headerName: 'Lok', 
        width: 60,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
       },
      {
        field: 'isi',
        headerName: 'Isi',
        width:60,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
      },
      {
        field: 'tapel',
        headerName: 'Tapel',
        width:60,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
      },
      {
        field: 'tinggi',
        headerName: 'Tinggi',
        width:60,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
      },
      {
        field: 'sisa',
        headerName: 'Sisa',
        width:60,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
      },
      {
        field: 'physical_stock',
        headerName: 'Physical Stock',
        width: 150,
        // Not editable — always derived from tapel * tinggi + sisa
        valueGetter: (p) => {
          const tapel = Number(p.data?.tapel) || 0;
          const tinggi = Number(p.data?.tinggi) || 0;
          const sisa = Number(p.data?.sisa) || 0;
          return tapel * tinggi + sisa;
        },
      },
      { field: 'omega_stock', headerName: 'Omega Stock', width: 150 },
      {
        field: 'difference',
        headerName: 'Difference',
        width: 130,
        cellStyle: (p) => (p.value !== 0 ? { color: '#dc2626', fontWeight: 600 } : null),
      },
      { field: 'notes', headerName: 'Notes', flex: 1, editable: true },
      {
        field: 'updated_at',
        headerName: 'Updated At',
        valueFormatter: (p) => (p.value ? new Date(p.value).toLocaleString() : ''),
      },
    ],
    []
  );

  const handleSave = useCallback(async () => {
    if (!gridRef.current) return;
    setSaving(true);
    setError(null);

    const entries: any[] = [];
    gridRef.current.api.forEachNode((node) => {
      if (!node.data) return;
      const location = Number(node.data.location) || 0;
      const tapel = Number(node.data.tapel) || 0;
      const tinggi = Number(node.data.tinggi) || 0;
      const sisa = Number(node.data.sisa) || 0;
      entries.push({
        id: node.data.id,
        location,
        isi: Number(node.data.isi) || 0,
        tapel,
        tinggi,
        sisa,
        physical_stock: tapel * tinggi + sisa, // computed, matches the column
        notes: node.data.notes,
      });
    });

    try {
      const resp = await fetch(`${API_BASE}/api/logs/${logId}/entries/update`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ entries }),
      });

      const data = await resp.json();
      console.log(resp.status, data);

      if (!resp.ok) {
        throw new Error(data.message ?? `Save failed: ${resp.status}`);
      }
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to save entries');
    } finally {
      setSaving(false);
    }
  }, [logId]);

  const openAddModal = useCallback(() => {
    setShowAddModal(true);
    setSelectedBarangId('');
    setLoadingOptions(true);

    fetch(`${API_BASE}/api/barangs`, {
      headers: {
        Accept: 'application/json',
      },
    })
      .then((resp) => {
        if (!resp.ok) {
          throw new Error(`Request failed: ${resp.status}`);
        }

        return resp.json();
      })
      .then((data) => {
        const optionsList = Array.isArray(data) ? data : data.data ?? [];
        setBarangOptions(optionsList);
      })
      .catch((err) => {
        console.error(err);
        setBarangOptions([]);
      })
      .finally(() => {
        setLoadingOptions(false);
      });
  }, []);

  const handleConfirmAdd = useCallback(async () => {
    if (!selectedBarangId || !logId || logId === 'undefined') {
      setError("Invalid Log ID or no item selected.");
      return;
    }

    setAddingRow(true);
    setError(null);

    try {
      const resp = await fetch(
        `${API_BASE}/api/logs/${logId}/entries`,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify({
            barang_id: Number(selectedBarangId),
          }),
        }
      );

      const data = await resp.json();

      if (!resp.ok) {
        throw new Error(
          data.message ?? `Add failed: ${resp.status}`
        );
      }

      setRowData((prev) => [...prev, data]);
      setShowAddModal(false);

    } catch (err) {
      setError(
        err instanceof Error
          ? err.message
          : 'Failed to add entry'
      );
    } finally {
      setAddingRow(false);
    }
  }, [selectedBarangId, logId])

  const handleRemove = useCallback(async () => {
    if (!selectedRow) return;
    const confirmed = window.confirm(
      `Remove entry for "${selectedRow.barang?.kode ?? selectedRow.barang_id}" at location ${selectedRow.location}?`
    );
    if (!confirmed) return;

    setRemoving(true);
    setError(null);
    try {
      const resp = await fetch(`${API_BASE}/api/logs/${logId}/entries/${selectedRow.id}`, {
        method: 'DELETE',
        headers: { Accept: 'application/json' },
      });
      const data = await resp.json();
      if (!resp.ok) throw new Error(data.message ?? `Remove failed: ${resp.status}`);

      setRowData((prev) => prev.filter((r) => r.id !== selectedRow.id));
      setSelectedRow(null);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to remove entry');
    } finally {
      setRemoving(false);
    }
  }, [selectedRow, logId]);

  return (
    <>
      <FloatingNavbar
        onBack={() => router.push('/logs')}
        onAdd={openAddModal}
        onRemove={handleRemove}
        canRemove={!!selectedRow}
        removing={removing}
        onSave={handleSave}
        saving={saving}                
      />

      <main className="w-full pt-25 pb-6">
        <h1 className="text-xl font-semibold mb-4">
          Entries {log ? `— ${new Date(log.recorded_on).toLocaleDateString()}` : ''}
        </h1>

        {error && (
          <div className="mb-2 text-sm text-red-600 px-6" >Couldn't load entries: {error}</div>
        )}

        <div className="grid-container w-full">
          <div className="ag-theme-quartz w-full">
            <AgGridReact<EntryRow>
              ref={gridRef}
              theme="legacy"
              domLayout="autoHeight"
              columnDefs={columnDefs}
              rowData={rowData}
              onGridReady={onGridReady}
              onCellValueChanged={onCellValueChanged}
              loading={loading}
              autoSizeStrategy={{ type: 'fitCellContents' }}
              defaultColDef={{ sortable: true, resizable: true }}
              rowSelection="single"
              onSelectionChanged={onSelectionChanged}
            />
          </div>
        </div>
      </main>

      {showAddModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/40">
          <div className="w-full max-w-md rounded-xl bg-white p-6 text-gray-900 shadow-xl">

            <h2 className="mb-4 text-lg font-semibold">
              Add Entry
            </h2>

            {/* BARANG */}
            <label className="mb-1 block text-sm font-medium">
              Barang
            </label>

            <select
              value={selectedBarangId}
              onChange={(e) => setSelectedBarangId(e.target.value)}
              className="mb-4 w-full rounded-lg border px-3 py-2"
              disabled={loadingOptions}
            >
              <option value="">
                {loadingOptions ? 'Loading options…' : 'Select barang'}
              </option>

              {barangOptions.map((barang) => (
                <option key={barang.id} value={barang.id}>
                  {barang.kode}
                </option>
              ))}
            </select>

            {/* BUTTONS */}
            <div className="mt-6 flex justify-end gap-2">
              <button
                onClick={() => setShowAddModal(false)}
                className="rounded-lg border px-4 py-2"
              >
                Cancel
              </button>

              <button
                type="button"
                onClick={handleConfirmAdd}
                disabled={!selectedBarangId || addingRow}
                className="rounded-lg bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
              >
                {addingRow ? 'Adding…' : 'Add'}
              </button>
            </div>

          </div>
        </div>
      )}
    </>
  );
}