// frontend/app/entries/[logId]/page.tsx
'use client';

import React, { useState, useMemo, useCallback } from 'react';
import { useParams, useRouter } from 'next/navigation';
import { AgGridReact } from 'ag-grid-react';
import {
  ColDef,
  GridReadyEvent,
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

export default function EntriesPage() {
  const router = useRouter();
  const params = useParams<{ logId: string }>();
  const logId = params.logId;

  const [rowData, setRowData] = useState<EntryRow[]>([]);
  const [log, setLog] = useState<LogSummary | null>(null);
  const [loading, setLoading] = useState(true);
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

  const columnDefs = useMemo<ColDef<EntryRow>[]>(
    () => [
      { field: 'id', headerName: 'ID', width: 90 },
      {
        field: 'barang.name' as any,
        headerName: 'Item',
        flex: 1.5,
        valueGetter: (p) => p.data?.barang?.name ?? `#${p.data?.barang_id}`,
      },
      { field: 'location', headerName: 'Location', width: 120 },
      { field: 'physical_stock', headerName: 'Physical Stock', width: 150 },
      { field: 'omega_stock', headerName: 'Omega Stock', width: 150 },
      {
        field: 'difference',
        headerName: 'Difference',
        width: 130,
        cellStyle: (p) =>
          p.value !== 0 ? { color: '#dc2626', fontWeight: 600 } : null,
      },
      { field: 'notes', headerName: 'Notes', flex: 1 },
      {
        field: 'updated_at',
        headerName: 'Updated At',
        valueFormatter: (p) => (p.value ? new Date(p.value).toLocaleString() : ''),
      },
    ],
    []
  );

  return (
    <main className="p-6">
      <button
        onClick={() => router.push('/logs')}
        className="mb-4 text-sm text-blue-600 hover:underline"
      >
        ← Back to Logs
      </button>

      <h1 className="text-xl font-semibold mb-4">
        Entries {log ? `— ${new Date(log.recorded_on).toLocaleDateString()}` : ''}
      </h1>

      {error && (
        <div className="mb-2 text-sm text-red-600">Couldn't load entries: {error}</div>
      )}

      <div className="grid-container">
        <div className="ag-theme-quartz" style={{ height: 600, width: '100%' }}>
          <AgGridReact<EntryRow>
            theme="legacy"
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
  );
}