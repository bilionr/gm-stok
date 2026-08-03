import React, { forwardRef, useImperativeHandle, useRef, useState, useCallback } from 'react';
import type { ColDef, ValueGetterParams, GridReadyEvent, ColumnState } from 'ag-grid-community';
import { AllCommunityModule } from 'ag-grid-community';
import { AgGridProvider, AgGridReact } from 'ag-grid-react';

export interface IRow {
  barang: string;
  lokasi: number;
  isi: number;
  tapel: number;
  tinggi: number;
  col0: number;
  col6: string;
  omega: string;
  a: string;
  cttn: string;
}

export interface GridExampleHandle {
  addRow: () => void;
  saveLayout: () => void;
}

const LAYOUT_KEY = 'grid-layout-v1';
const DATA_KEY = 'grid-data-v1';

const emptyRow: IRow = {
  barang: '', lokasi: 0, isi: 0, tapel: 0, tinggi: 0, col0: 0, col6: '', omega: '', a: '', cttn: '',
};

const defaultRows: IRow[] = [
  { barang: 'DLSIP50', lokasi: 11, isi: 1, tapel: 7, tinggi: 8, col0: 0, col6: '63', omega: '61', a: '2', cttn: '' },
  { barang: 'DLPG50', lokasi: 11, isi: 1, tapel: 5, tinggi: 7, col0: 4, col6: '45,0', omega: '45', a: '0', cttn: '' },
  { barang: 'TLM25', lokasi: 11, isi: 1, tapel: 5, tinggi: 8, col0: 0, col6: '40', omega: '0', a: '', cttn: '' },
  { barang: 'DLBOLA50', lokasi: 11, isi: 1, tapel: 5, tinggi: 13, col0: 0, col6: '84', omega: '84', a: '0', cttn: '' },
  { barang: 'TC25', lokasi: 11, isi: 1, tapel: 0, tinggi: 0, col0: 9, col6: '9', omega: '0', a: '', cttn: '' },
  { barang: 'GLP50', lokasi: 12, isi: 1, tapel: 7, tinggi: 7, col0: 6, col6: '61', omega: '0', a: '', cttn: '' },
  { barang: 'KJRS25', lokasi: 13, isi: 1, tapel: 7, tinggi: 9, col0: 3, col6: '117', omega: '0', a: '', cttn: '' },
  { barang: 'KJGR25', lokasi: 13, isi: 1, tapel: 11, tinggi: 7, col0: 10, col6: '87', omega: '0', a: '', cttn: '' },
  { barang: 'TPYG25', lokasi: 21, isi: 1, tapel: 5, tinggi: 4, col0: 2, col6: '22', omega: '0', a: '', cttn: '' },
  { barang: 'TTULIP25', lokasi: 21, isi: 1, tapel: 7, tinggi: 8, col0: 5, col6: '161', omega: '0', a: '', cttn: '' },
  { barang: 'TTULIP25', lokasi: 22, isi: 1, tapel: 5, tinggi: 20, col0: 0, col6: '161', omega: '0', a: '', cttn: '' },
  { barang: 'KJRS25', lokasi: 22, isi: 1, tapel: 5, tinggi: 10, col0: 1, col6: '117', omega: '0', a: '', cttn: '' },
  { barang: 'KJRS1/2', lokasi: 22, isi: 1, tapel: 5, tinggi: 0, col0: 0, col6: '18', omega: '0', a: '', cttn: '' },
  { barang: 'KJRS1/2', lokasi: 31, isi: 1, tapel: 5, tinggi: 2, col0: 4, col6: '18', omega: '0', a: '', cttn: '' },
  { barang: 'KJGB25', lokasi: 31, isi: 1, tapel: 5, tinggi: 10, col0: 0, col6: '50', omega: '0', a: '', cttn: '' },
  { barang: 'MIELOS', lokasi: 33, isi: 1, tapel: 5, tinggi: 4, col0: 3, col6: '81', omega: '0', a: '', cttn: '' },
  { barang: 'TS05', lokasi: 33, isi: 1, tapel: 0, tinggi: 0, col0: 40, col6: '41', omega: '0', a: '', cttn: '' },
  { barang: 'TB1/2', lokasi: 33, isi: 1, tapel: 10, tinggi: 10, col0: 5, col6: '246', omega: '0', a: '', cttn: '' },
  { barang: 'TLM1', lokasi: 33, isi: 1, tapel: 0, tinggi: 0, col0: 6, col6: '29', omega: '0', a: '', cttn: '' },
  { barang: 'TSEG25', lokasi: 41, isi: 1, tapel: 7, tinggi: 7, col0: 4, col6: '53', omega: '0', a: '', cttn: '' },
  { barang: 'DLSINTA50', lokasi: 41, isi: 1, tapel: 5, tinggi: 9, col0: 2, col6: '55', omega: '55', a: '0', cttn: '' },
  { barang: 'TC1', lokasi: 42, isi: 1, tapel: 4, tinggi: 5, col0: 2, col6: '22', omega: '0', a: '', cttn: '' },
  { barang: 'TS05', lokasi: 42, isi: 1, tapel: 0, tinggi: 0, col0: 1, col6: '41', omega: '0', a: '', cttn: '' },
  { barang: 'MINFORTPLW', lokasi: 51, isi: 1, tapel: 0, tinggi: 0, col0: 14, col6: '14', omega: '0', a: '', cttn: '' },
  { barang: 'TS1', lokasi: 51, isi: 1, tapel: 0, tinggi: 0, col0: 21, col6: '28', omega: '0', a: '', cttn: '' },
  { barang: 'DLBOLA50', lokasi: 52, isi: 1, tapel: 0, tinggi: 0, col0: 6, col6: '84', omega: '84', a: '0', cttn: '' },
  { barang: 'GLP50', lokasi: 52, isi: 1, tapel: 0, tinggi: 0, col0: 6, col6: '61', omega: '0', a: '', cttn: '' },
  { barang: 'DLSINTA50', lokasi: 52, isi: 1, tapel: 0, tinggi: 0, col0: 8, col6: '55', omega: '55', a: '0', cttn: '' },
  { barang: 'DLPG50', lokasi: 71, isi: 1, tapel: 0, tinggi: 0, col0: 6, col6: '45', omega: '45', a: '0', cttn: '' },
  { barang: 'MINTW1', lokasi: 71, isi: 1, tapel: 0, tinggi: 0, col0: 10, col6: '10', omega: '0', a: '', cttn: '' },
  { barang: 'DLBOLA50', lokasi: 71, isi: 1, tapel: 0, tinggi: 0, col0: 13, col6: '84', omega: '84', a: '0', cttn: '' },
  { barang: 'B5P', lokasi: 71, isi: 1, tapel: 0, tinggi: 0, col0: 68, col6: '107', omega: '108', a: '-1', cttn: '' },
  { barang: 'DLSIP50', lokasi: 71, isi: 1, tapel: 0, tinggi: 0, col0: 7, col6: '63', omega: '61', a: '2', cttn: '' },
  { barang: 'B5P', lokasi: 71, isi: 1, tapel: 0, tinggi: 0, col0: 39, col6: '107', omega: '108', a: '-1', cttn: '' },
  { barang: 'TB1/2', lokasi: 71, isi: 1, tapel: 0, tinggi: 0, col0: 0, col6: '246', omega: '0', a: '', cttn: '' },
  { barang: 'TLM1', lokasi: 72, isi: 1, tapel: 0, tinggi: 0, col0: 14, col6: '29', omega: '0', a: '', cttn: '' },
  { barang: 'KJRS1/2', lokasi: 72, isi: 1, tapel: 0, tinggi: 0, col0: 4, col6: '18', omega: '0', a: '', cttn: '' },
  { barang: 'BOB25P', lokasi: 81, isi: 1, tapel: 5, tinggi: 4, col0: 0, col6: '20', omega: '0', a: '', cttn: '' },
  { barang: 'B10P', lokasi: 81, isi: 1, tapel: 7, tinggi: 7, col0: 5, col6: '54', omega: '0', a: '', cttn: '' },
  { barang: 'RG', lokasi: 81, isi: 1, tapel: 8, tinggi: 5, col0: 4, col6: '198', omega: '0', a: '', cttn: '' },
  { barang: 'RG', lokasi: 81, isi: 50, tapel: 0, tinggi: 0, col0: 3, col6: '198', omega: '0', a: '', cttn: '' },
  { barang: 'MIELOS', lokasi: 81, isi: 1, tapel: 4, tinggi: 7, col0: 2, col6: '81', omega: '0', a: '', cttn: '' },
  { barang: 'MIELOS', lokasi: 82, isi: 1, tapel: 4, tinggi: 7, col0: 0, col6: '81', omega: '0', a: '', cttn: '' },
  { barang: 'G5', lokasi: 82, isi: 1, tapel: 0, tinggi: 0, col0: 9, col6: '19', omega: '20', a: '-1', cttn: '' },
  { barang: 'G5', lokasi: 82, isi: 2, tapel: 0, tinggi: 0, col0: 5, col6: '19', omega: '20', a: '-1', cttn: '' },
  { barang: 'RG', lokasi: 84, isi: 1, tapel: 0, tinggi: 0, col0: 4, col6: '198', omega: '0', a: '', cttn: '' },
  { barang: 'MTGAMD15', lokasi: 110, isi: 1, tapel: 0, tinggi: 0, col0: 1, col6: '1', omega: '0', a: '', cttn: '' },
  { barang: 'TS1', lokasi: 110, isi: 1, tapel: 0, tinggi: 0, col0: 7, col6: '28', omega: '0', a: '', cttn: '' },
  { barang: 'HW', lokasi: 110, isi: 1, tapel: 5, tinggi: 4, col0: 3, col6: '23', omega: '0', a: '', cttn: '' },
  { barang: 'TB1/2', lokasi: 110, isi: 1, tapel: 10, tinggi: 10, col0: 0, col6: '246', omega: '0', a: '', cttn: '' },
  { barang: 'MTGAMD5', lokasi: 120, isi: 1, tapel: 0, tinggi: 0, col0: 15, col6: '15', omega: '0', a: '', cttn: '' },
  { barang: 'TLM1', lokasi: 120, isi: 1, tapel: 0, tinggi: 0, col0: 9, col6: '29', omega: '0', a: '', cttn: '' },
  { barang: 'KRN', lokasi: 120, isi: 10, tapel: 5, tinggi: 5, col0: 4, col6: '290', omega: '0', a: '', cttn: '' },
  { barang: 'TB1/2', lokasi: 130, isi: 1, tapel: 0, tinggi: 0, col0: 3, col6: '246', omega: '0', a: '', cttn: '' },
  { barang: 'KJGL1/2', lokasi: 130, isi: 1, tapel: 5, tinggi: 0, col0: 3, col6: '249', omega: '0', a: '', cttn: '' },
  { barang: 'KJGL1/2', lokasi: 130, isi: 1, tapel: 5, tinggi: 30, col0: 0, col6: '249', omega: '0', a: '', cttn: '' },
  { barang: 'KJGL1/2', lokasi: 130, isi: 1, tapel: 5, tinggi: 19, col0: 1, col6: '249', omega: '0', a: '', cttn: '' },
  { barang: 'KJGL1/2', lokasi: 130, isi: 1, tapel: 5, tinggi: 0, col0: 0, col6: '249', omega: '0', a: '', cttn: '' },
  { barang: 'TB1/2', lokasi: 140, isi: 1, tapel: 6, tinggi: 3, col0: 0, col6: '246', omega: '0', a: '', cttn: '' },
  { barang: 'TB1/2', lokasi: 140, isi: 1, tapel: 6, tinggi: 3, col0: 2, col6: '246', omega: '0', a: '', cttn: '' },
  { barang: 'GRMD', lokasi: 140, isi: 1, tapel: 10, tinggi: 20, col0: 0, col6: '483', omega: '0', a: '', cttn: '' },
  { barang: 'GRMD', lokasi: 140, isi: 1, tapel: 10, tinggi: 20, col0: 0, col6: '483', omega: '0', a: '', cttn: '' },
  { barang: 'GRMD', lokasi: 140, isi: 1, tapel: 7, tinggi: 11, col0: 6, col6: '483', omega: '0', a: '', cttn: '' },
  { barang: 'MINCUR', lokasi: 150, isi: 4, tapel: 0, tinggi: 0, col0: 13, col6: '487', omega: '0', a: '', cttn: '' },
  { barang: 'MINCUR', lokasi: 150, isi: 10, tapel: 0, tinggi: 0, col0: 3, col6: '487', omega: '0', a: '', cttn: '' },
  { barang: 'MINCUR', lokasi: 150, isi: 15, tapel: 0, tinggi: 0, col0: 11, col6: '487', omega: '0', a: '', cttn: '' },
  { barang: 'MINCUR', lokasi: 150, isi: 20, tapel: 0, tinggi: 0, col0: 12, col6: '487', omega: '0', a: '', cttn: '' },
  { barang: 'MINCUR', lokasi: 150, isi: 1, tapel: 0, tinggi: 0, col0: 0, col6: '487', omega: '0', a: '', cttn: '' },
  { barang: 'MINCUR', lokasi: 160, isi: 0, tapel: 0, tinggi: 735, col0: 0, col6: '0', omega: '', a: '', cttn: '4053' },
];

const GridExample = forwardRef<GridExampleHandle>((_, ref) => {
  const gridRef = useRef<AgGridReact<IRow>>(null);

  // Always start from defaultRows — no localStorage involved for data
  const [rowData, setRowData] = useState<IRow[]>(defaultRows);

  const [colDefs] = useState<ColDef<IRow>[]>([
    { field: 'barang', headerName: 'Barang', minWidth: 60 },
    { field: 'lokasi', headerName: 'Lokasi', cellDataType: 'number', minWidth: 60 },
    { field: 'isi', headerName: 'Isi', cellDataType: 'number', minWidth: 60 },
    { field: 'tapel', headerName: 'Tapel', cellDataType: 'number', minWidth: 60 },
    { field: 'tinggi', headerName: 'Tinggi', cellDataType: 'number', minWidth: 60 },
    { field: 'col0', headerName: '0', cellDataType: 'number', minWidth: 60 },
    {
      colId: 'total',
      headerName: 'Total',
      editable: false,
      minWidth: 60,
      valueGetter: (params: ValueGetterParams<IRow>) => {
        const tapel = params.data?.tapel ?? 0;
        const tinggi = params.data?.tinggi ?? 0;
        const col0 = params.data?.col0 ?? 0;
        return tapel * tinggi + col0;
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
    setRowData((prev) => [...prev, { ...emptyRow }]);
  }, []);

  useImperativeHandle(ref, () => ({ addRow, saveLayout }), [addRow, saveLayout]);

  return (
    <AgGridProvider modules={[AllCommunityModule]}>
      <div className="grid-container" style={{ height: '500px', width: '100%' }}>
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