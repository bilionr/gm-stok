// frontend/app/logs/page.tsx
'use client';

import React, { useState, useMemo, useCallback } from 'react';
import { useRouter } from 'next/navigation';
import { AgGridReact } from 'ag-grid-react';
import {
  ColDef,
  GridReadyEvent,
  AllCommunityModule,
  ModuleRegistry,
} from 'ag-grid-community';
import type { CustomCellRendererProps } from 'ag-grid-react';

import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-quartz.css';

ModuleRegistry.registerModules([AllCommunityModule]);

const API_BASE = process.env.NEXT_PUBLIC_API_URL;

interface LogRow {
  id: number;
  recorded_on: string;
  entries_count: number;
  discrepancies_count: number;
  created_at: string;
  updated_at: string;
}

function CustomButtonComponent(props: CustomCellRendererProps<LogRow>) {
  const router = useRouter();

  const handleClick = () => {
    if (props.data?.id) {
      router.push(`/logs/${props.data.id}/entries/`);
    }
  };

  return (
    <button
      onClick={handleClick}
      className="px-3 py-1 rounded bg-blue-500 hover:bg-blue-600 text-white text-sm"
    >
      View
    </button>
  );
}

export default function LogsPage() {
  const [rowData, setRowData] = useState<LogRow[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const onGridReady = useCallback((params: GridReadyEvent) => {
    fetch(`${API_BASE}/api/logs`, { headers: { Accept: 'application/json' } })
      .then((resp) => {
        if (!resp.ok) throw new Error(`Request failed: ${resp.status}`);
        return resp.json();
      })
      .then((data: LogRow[]) => setRowData(data))
      .catch((err) =>
        setError(err instanceof Error ? err.message : 'Failed to load logs')
      )
      .finally(() => setLoading(false));
  }, []);

  const columnDefs = useMemo<ColDef<LogRow>[]>(
    () => [
      {
        field: 'actions' as any,
        headerName: 'Actions',
        cellRenderer: CustomButtonComponent,
        width: 120,
        sortable: false,
        filter: false,
      },
      { field: 'id', headerName: 'ID', width: 90 },
      {
        field: 'recorded_on',
        headerName: 'Recorded On',
        valueFormatter: (p) =>
          p.value
            ? new Date(p.value).toLocaleDateString(undefined, {
                year: 'numeric',
                month: 'short',
                day: '2-digit',
              })
            : '',
      },
      { field: 'entries_count', headerName: 'Entries', width: 110 },
      { field: 'discrepancies_count', headerName: 'Discrepancies', width: 140 },
      {
        field: 'created_at',
        headerName: 'Created At',
        valueFormatter: (p) => (p.value ? new Date(p.value).toLocaleString() : ''),
      },
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
      <h1 className="text-xl font-semibold mb-4">Logs</h1>

      {error && (
        <div className="mb-2 text-sm text-red-600">Couldn't load logs: {error}</div>
      )}

      <div className="grid-container">
        <div className="ag-theme-quartz" style={{ height: 600, width: '100%' }}>
          <AgGridReact<LogRow>
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