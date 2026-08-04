import React, { forwardRef, useImperativeHandle, useRef, useState, useCallback, useEffect } from 'react';
import type { ColDef, ValueGetterParams, GridReadyEvent, ColumnState, CellValueChangedEvent  } from 'ag-grid-community';
import { AllCommunityModule } from 'ag-grid-community';
import { AgGridProvider, AgGridReact } from 'ag-grid-react';

const API_URL = process.env.REACT_APP_API_URL ?? 'http://127.0.0.1:8000/api';

export interface IRow {
  id: number;
  barang: string;
  lokasi: number;
  isi: number;
  tapel: number;
  tinggi: number;
  sisa: number;
  col6: string;
  omega: string;
  a: string;
  cttn: string;
}

// shape actually coming back from Laravel right now
interface ApiStockEntry {
  id: number;
  item_id: number;
  barang: string;
  omega: number;
  lokasi: number;
  isi: number;
  tapel: number;
  tinggi: number;
  sisa: number;
  cttn: string;
}

const emptyRow: IRow = {
  id: 0,
  barang: '',
  lokasi: 0,
  isi: 1,
  tapel: 0,
  tinggi: 0,
  sisa: 0,
  col6: '',
  omega: '0',
  a: '',
  cttn: '',
};

const mapApiRowToIRow = (row: ApiStockEntry): IRow => ({
  id: row.id,
  barang: row.barang,
  lokasi: row.lokasi,
  isi: row.isi,
  tapel: row.tapel,
  tinggi: row.tinggi,
  sisa: row.sisa,
  col6: '', // not returned by API yet — see note below
  omega: String(row.omega),
  a: '', // not returned by API yet — see note below
  cttn: row.cttn,
});

export interface GridExampleHandle {
  addRow: () => void;
  saveLayout: () => void;
}

  const LAYOUT_KEY = 'grid-layout-v1';
  const DATA_KEY = 'grid-data-v1';

const GridExample = forwardRef<GridExampleHandle>((_, ref) => {
  const gridRef = useRef<AgGridReact<IRow>>(null);

  const [rowData, setRowData] = useState<IRow[]>();

  useEffect(() => {
    fetch(`${API_URL}/stock-entry`)
      .then((res) => res.json())
      .then((json: { data: ApiStockEntry[] }) => {
        setRowData(json.data.map(mapApiRowToIRow));
      })
      .catch((err) => console.error('Failed to load stock entries:', err));
  }, []);

  const [colDefs] = useState<ColDef<IRow>[]>([
    { field: 'barang', headerName: 'Barang', minWidth: 60 },
    { field: 'lokasi', headerName: 'Lokasi', cellDataType: 'number', minWidth: 60 },
    { field: 'isi', headerName: 'Isi', cellDataType: 'number', minWidth: 60 },
    { field: 'tapel', headerName: 'Tapel', cellDataType: 'number', minWidth: 60 },
    { field: 'tinggi', headerName: 'Tinggi', cellDataType: 'number', minWidth: 60 },
    { field: 'sisa', headerName: 'Sisa', cellDataType: 'number', minWidth: 60 },
    {
      colId: 'total',
      headerName: 'Total',
      editable: false,
      minWidth: 60,
      valueGetter: (params: ValueGetterParams<IRow>) => {
        const tapel = params.data?.tapel ?? 0;
        const tinggi = params.data?.tinggi ?? 0;
        const sisa = params.data?.sisa ?? 0;
        return tapel * tinggi + sisa;
      },
    },
    { field: 'col6', headerName: '6', minWidth: 60 },
    { field: 'omega', headerName: 'Omega', minWidth: 60 },
    { field: 'a', headerName: 'a', minWidth: 60 },
    { field: 'cttn', headerName: 'Cttn', minWidth: 60 },
  ]);

  const defaultColDef: ColDef = {
    editable: true,
    resizable: true,
  };

  const autoSize = useCallback(() => {
    gridRef.current?.api.autoSizeAllColumns();
  }, []);

  // Only saves column layout now, not data
  const saveLayout = useCallback(() => {
    const state: ColumnState[] | undefined = gridRef.current?.api.getColumnState();
    if (state) localStorage.setItem(LAYOUT_KEY, JSON.stringify(state));
  }, []);

  const onGridReady = useCallback((params: GridReadyEvent) => {
    autoSize();
    const saved = localStorage.getItem(LAYOUT_KEY);
    if (saved) {
      try {
        const state: ColumnState[] = JSON.parse(saved);
        params.api.applyColumnState({ state, applyOrder: true });
      } catch {
        // ignore corrupt state
      }
    }
  }, [autoSize]);

  const onColumnChanged = useCallback(() => {
    const state = gridRef.current?.api.getColumnState();
    if (state) localStorage.setItem(LAYOUT_KEY, JSON.stringify(state));
  }, []);

  const addRow = useCallback(() => {
    setRowData((prev) => [...(prev ?? []), { ...emptyRow }]);
  }, []);

  useImperativeHandle(ref, () => ({ addRow, saveLayout }), [addRow, saveLayout]);

  return (
    <AgGridProvider modules={[AllCommunityModule]}>
      <div className="grid-container" style={{ height: '1000px', width: '100%' }}>
        <AgGridReact
          ref={gridRef}
          rowData={rowData}
          columnDefs={colDefs}
          defaultColDef={defaultColDef}
          onGridReady={onGridReady}
          onColumnMoved={onColumnChanged}
          onColumnResized={(e) => { if (e.finished) onColumnChanged(); }}
        />
      </div>
    </AgGridProvider>
  );
});

GridExample.displayName = 'GridExample';

export default GridExample;