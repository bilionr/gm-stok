// frontend/app/entries/[logId]/page.tsx
'use client';

import React, { useState, useMemo, useCallback, useRef, useEffect} from 'react';
import { useParams, useRouter } from 'next/navigation';
import { AgGridReact } from 'ag-grid-react';
import {
  ColDef,
  GridReadyEvent,
  CellValueChangedEvent,
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
  barang?: { id: number; name: string };
}

interface LogSummary {
  id: number;
  recorded_on: string;
}

interface FloatingNavbarProps {
  title?: string;
  onBack?: () => void;
  onSave?: () => void;
  saving?: boolean;
}

function FloatingNavbar({ title = '', onBack, onSave, saving }: FloatingNavbarProps) {
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
      {title && <span className="text-sm font-semibold text-gray-900">{title}</span>}
      {onSave && (
        <button
          onClick={onSave}
          disabled={saving}
          className="text-sm font-medium px-3 py-1 rounded-full bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 transition-colors"
        >
          {saving ? 'Saving…' : 'Save'}
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
  const [error, setError] = useState<string | null>(null);

  const onGridReady = useCallback(
    (gridParams: GridReadyEvent) => {
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
    },
    [logId]
  );

 // Recompute the physical_stock cell whenever isi/tapel/tinggi/sisa is edited
  const onCellValueChanged = useCallback((event: CellValueChangedEvent<EntryRow>) => {
    event.api.refreshCells({ rowNodes: [event.node], force: true });
  }, []);

  const columnDefs = useMemo<ColDef<EntryRow>[]>(
    () => [
      {
        field: 'barang.kode' as any,
        headerName: 'Item',
        flex: 1.5,
        valueGetter: (p) => p.data?.barang?.kode ?? `#${p.data?.barang_id}`,
      },
      { field: 'location', headerName: 'Location', width: 110 },
      {
        field: 'isi',
        headerName: 'Isi',
        width: 90,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
      },
      {
        field: 'tapel',
        headerName: 'Tapel',
        width: 90,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
      },
      {
        field: 'tinggi',
        headerName: 'Tinggi',
        width: 90,
        editable: true,
        type: 'numericColumn',
        valueParser: (p) => Number(p.newValue) || 0,
      },
      {
        field: 'sisa',
        headerName: 'Sisa',
        width: 90,
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
      const tapel = Number(node.data.tapel) || 0;
      const tinggi = Number(node.data.tinggi) || 0;
      const sisa = Number(node.data.sisa) || 0;
      entries.push({
        id: node.data.id,
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
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ entries }),
      });
      if (!resp.ok) throw new Error(`Save failed: ${resp.status}`);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to save entries');
    } finally {
      setSaving(false);
    }
  }, [logId]);

  return (
    <>
      <FloatingNavbar
        title={log ? new Date(log.recorded_on).toLocaleDateString() : 'Entries'}
        onBack={() => router.push('/logs')}
        onSave={handleSave}
        saving={saving}
      />

      <main className="w-full py-6">
        <h1 className="text-xl font-semibold mb-4">
          Entries {log ? `— ${new Date(log.recorded_on).toLocaleDateString()}` : ''}
        </h1>

        {error && (
          <div className="mb-2 text-sm text-red-600" px-6>Couldn't load entries: {error}</div>
        )}

        <div className="grid-container w-full">
          <div className="ag-theme-quartz w-full">
            <AgGridReact<EntryRow>
              theme="legacy"
              domLayout="autoHeight"
              columnDefs={columnDefs}
              rowData={rowData}
              onGridReady={onGridReady}
              loading={loading}
              autoSizeStrategy={{ type: 'fitCellContents' }}
              defaultColDef={{ sortable: true, filter: true, resizable: true }}
            />
          </div>
        </div>
      </main>
    </>
  );
}