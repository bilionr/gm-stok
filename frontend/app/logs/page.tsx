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
  const router = useRouter();
  const [rowData, setRowData] = useState<LogRow[]>([]);
  const [loading, setLoading] = useState(true);
  const [adding, setAdding] = useState(false);
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

  const handleAddLog = useCallback(async () => {
    setAdding(true);
    setError(null);
    try {
      const resp = await fetch(`${API_BASE}/api/logs`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({}), // omit recorded_on -> backend defaults to today
      });
      const newLog: LogRow = await resp.json();
      router.push(`/logs/${newLog.id}/entries`); // jump straight into the new log's entries
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to create log');
    } finally {
      setAdding(false);
    }
  }, [router]);

  const handleDeleteLog = useCallback(async (id: number) => {
    if (!confirm(`Are you sure you want to delete Log #${id}?`)) return;

    try {
      const resp = await fetch(`${API_BASE}/api/logs/${id}`, {
        method: 'DELETE',
        headers: { Accept: 'application/json' },
      });

      if (!resp.ok) throw new Error('Failed to delete log');

      // Update state to remove deleted row
      setRowData((prev) => prev.filter((row) => row.id !== id));
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to delete log');
    }
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
      {
        headerName: 'Delete',
        width: 100,
        sortable: false,
        filter: false,
        cellRenderer: (props: CustomCellRendererProps<LogRow>) => (
          <button
            onClick={(e) => {
              e.stopPropagation();
              if (props.data?.id) handleDeleteLog(props.data.id);
            }}
            className="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white text-xs font-medium"
          >
            Delete
          </button>
        ),
      },
    ],
    []
  );

  return (
    <main className="p-6">
      <div className="flex items-center justify-between mb-4">
        <h1 className="text-xl font-semibold">Logs</h1>
        <button
          onClick={handleAddLog}
          disabled={adding}
          className="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium disabled:opacity-50"
        >
          {adding ? 'Adding…' : '+ Add'}
        </button>
      </div>

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