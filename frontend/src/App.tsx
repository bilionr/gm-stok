import React, { useRef } from 'react';
import Navbar from './components/Navbar/Navbar';
import GridExample, { GridExampleHandle } from './components/GridExample';
import './App.css';

function App() {
  const gridRef = useRef<GridExampleHandle>(null);

  return (
    <div className="App">
      <Navbar
        onAddRow={() => gridRef.current?.addRow()}
        onSave={() => gridRef.current?.saveLayout()}
      />
      <GridExample ref={gridRef} />
    </div>
  );
}

export default App;