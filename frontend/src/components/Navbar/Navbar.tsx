import React from 'react';
import './Navbar.css';

interface NavbarProps {
  onAddRow: () => void;
  onSave: () => void;
}

const Navbar = ({ onAddRow, onSave }: NavbarProps) => {
  return (
    <nav className="floating-navbar">
      <div className="navbar-links">
        <button className="navbar-button" onClick={onAddRow}>Tambah</button>
        <button className="navbar-button navbar-button-primary" onClick={onSave}>Simpan</button>
      </div>
    </nav>
  );
};

export default Navbar;