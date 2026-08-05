import React, { useEffect, useState, useCallback, useMemo } from 'react';
import { Check, Flag, Loader2, Inbox, ClipboardList, PackageSearch } from 'lucide-react';

/**
 * ----------------------------------------------------------------------------
 * STOCK-OPNAME LOG BOARD
 * ----------------------------------------------------------------------------
 * Maps to the Laravel schema:
 *   logs             -> one row per audit date (recorded_on)
 *   entries          -> one row per (log, barang, location): physical_stock,
 *                       omega_stock, difference, notes
 *   barang            -> item master (kode, omega running total)
 *   barang_locations  -> valid (barang, location) pairs
 *
 * Expected endpoints (adjust to match your API):
 *   GET /api/omega-logs            -> { data: LogSummary[] }
 *   GET /api/omega-logs/:date      -> { data: LogEntry[] }
 *
 * If the API is unreachable (e.g. previewing this file on its own), the
 * component falls back to demo data so the design is still visible.
 * ----------------------------------------------------------------------------
 */

const API_URL = (typeof process !== 'undefined' && process.env && process.env.REACT_APP_API_URL) || 'http://127.0.0.1:8000/api';

const DEMO_DATES = [
  { recorded_on: '2026-08-05', item_count: 32, discrepancy_count: 0 },
  { recorded_on: '2026-08-04', item_count: 32, discrepancy_count: 3 },
  { recorded_on: '2026-08-03', item_count: 31, discrepancy_count: 1 },
  { recorded_on: '2026-08-02', item_count: 32, discrepancy_count: 0 },
  { recorded_on: '2026-08-01', item_count: 30, discrepancy_count: 5 },
];

const DEMO_ENTRIES = {
  '2026-08-05': [
    { barang: 'BRG-0012', location: 1, physical_stock: 84, omega_stock: 84, difference: 0, notes: null },
    { barang: 'BRG-0031', location: 2, physical_stock: 12, omega_stock: 12, difference: 0, notes: null },
    { barang: 'BRG-0044', location: 1, physical_stock: 6, omega_stock: 6, difference: 0, notes: null },
  ],
  '2026-08-04': [
    { barang: 'BRG-0012', location: 1, physical_stock: 80, omega_stock: 84, difference: -4, notes: 'Dus rusak, dibuang' },
    { barang: 'BRG-0031', location: 2, physical_stock: 14, omega_stock: 12, difference: 2, notes: 'Belum input transfer masuk' },
    { barang: 'BRG-0044', location: 1, physical_stock: 6, omega_stock: 6, difference: 0, notes: null },
    { barang: 'BRG-0058', location: 3, physical_stock: 9, omega_stock: 11, difference: -2, notes: null },
  ],
  '2026-08-03': [
    { barang: 'BRG-0012', location: 1, physical_stock: 84, omega_stock: 84, difference: 0, notes: null },
    { barang: 'BRG-0058', location: 3, physical_stock: 10, omega_stock: 11, difference: -1, notes: 'Selisih kecil, cek ulang besok' },
  ],
  '2026-08-02': [
    { barang: 'BRG-0012', location: 1, physical_stock: 84, omega_stock: 84, difference: 0, notes: null },
    { barang: 'BRG-0031', location: 2, physical_stock: 12, omega_stock: 12, difference: 0, notes: null },
  ],
  '2026-08-01': [
    { barang: 'BRG-0012', location: 1, physical_stock: 79, omega_stock: 84, difference: -5, notes: 'Stock opname awal bulan' },
    { barang: 'BRG-0031', location: 2, physical_stock: 15, omega_stock: 12, difference: 3, notes: null },
    { barang: 'BRG-0044', location: 1, physical_stock: 4, omega_stock: 6, difference: -2, notes: null },
    { barang: 'BRG-0058', location: 3, physical_stock: 8, omega_stock: 11, difference: -3, notes: 'Perlu investigasi' },
    { barang: 'BRG-0099', location: 2, physical_stock: 20, omega_stock: 22, difference: -2, notes: null },
  ],
};

function formatDate(iso) {
  const d = new Date(`${iso}T00:00:00`);
  const day = d.toLocaleDateString('id-ID', { day: '2-digit' });
  const month = d.toLocaleDateString('id-ID', { month: 'short' }).toUpperCase();
  const year = d.toLocaleDateString('id-ID', { year: 'numeric' });
  const weekday = d.toLocaleDateString('id-ID', { weekday: 'long' });
  return { day, month, year, weekday };
}

function Stamp({ count, size = 'md' }) {
  const clean = count === 0;
  return (
    <div className={`stamp stamp--${size} ${clean ? 'stamp--clean' : 'stamp--flagged'}`}>
      <div className="stamp__ring">
        {clean ? <Check size={size === 'lg' ? 22 : 14} strokeWidth={3} /> : <Flag size={size === 'lg' ? 20 : 13} strokeWidth={2.5} />}
        <span className="stamp__label">{clean ? 'COCOK' : `${count} SELISIH`}</span>
      </div>
    </div>
  );
}

const OmegaLogList = () => {
  const [dates, setDates] = useState([]);
  const [selectedDate, setSelectedDate] = useState(null);
  const [entries, setEntries] = useState([]);
  const [loadingList, setLoadingList] = useState(true);
  const [loadingDetail, setLoadingDetail] = useState(false);
  const [usingDemoData, setUsingDemoData] = useState(false);

  useEffect(() => {
    let cancelled = false;

    fetch(`${API_URL}/omega-logs`)
      .then((res) => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then((json) => {
        if (cancelled) return;
        setDates(json.data ?? []);
        setUsingDemoData(false);
      })
      .catch(() => {
        if (cancelled) return;
        setDates(DEMO_DATES);
        setUsingDemoData(true);
      })
      .finally(() => {
        if (!cancelled) setLoadingList(false);
      });

    return () => {
      cancelled = true;
    };
  }, []);

  const handleSelect = useCallback(
    (date) => {
      setSelectedDate(date);
      setLoadingDetail(true);

      if (usingDemoData) {
        window.setTimeout(() => {
          setEntries(DEMO_ENTRIES[date] ?? []);
          setLoadingDetail(false);
        }, 260);
        return;
      }

      fetch(`${API_URL}/omega-logs/${date}`)
        .then((res) => {
          if (!res.ok) throw new Error(`HTTP ${res.status}`);
          return res.json();
        })
        .then((json) => setEntries(json.data ?? []))
        .catch(() => setEntries(DEMO_ENTRIES[date] ?? []))
        .finally(() => setLoadingDetail(false));
    },
    [usingDemoData]
  );

  const summary = useMemo(() => {
    const discrepancies = entries.filter((e) => e.difference !== 0);
    const net = entries.reduce((sum, e) => sum + e.difference, 0);
    return { total: entries.length, discrepancies: discrepancies.length, net };
  }, [entries]);

  const selected = dates.find((d) => d.recorded_on === selectedDate);

  return (
    <div className="board">
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap');

        .board {
          --paper: #f3f0e8;
          --paper-raised: #eae4d2;
          --ink: #201d1a;
          --ink-soft: #5a5349;
          --steel: #3d5266;
          --steel-soft: #7c8fa0;
          --signal: #f2b705;
          --signal-ink: #4a3900;
          --green: #3f7d52;
          --green-bg: #e3ede4;
          --red: #b23a2e;
          --red-bg: #f6e4e1;
          --line: #d8d0ba;

          font-family: 'Inter', system-ui, sans-serif;
          background: var(--paper);
          color: var(--ink);
          min-height: 100%;
          padding: 28px;
          box-sizing: border-box;
        }
        .board *, .board *::before, .board *::after { box-sizing: border-box; }

        .board__frame {
          max-width: 1040px;
          margin: 0 auto;
          display: grid;
          grid-template-columns: 300px 1fr;
          gap: 20px;
        }
        @media (max-width: 780px) {
          .board__frame { grid-template-columns: 1fr; }
        }

        .board__title {
          grid-column: 1 / -1;
          display: flex;
          align-items: baseline;
          gap: 10px;
          margin-bottom: 4px;
        }
        .board__title h1 {
          font-family: 'Oswald', sans-serif;
          font-weight: 600;
          letter-spacing: 0.03em;
          text-transform: uppercase;
          font-size: 22px;
          margin: 0;
        }
        .board__title span {
          font-family: 'IBM Plex Mono', monospace;
          font-size: 12px;
          color: var(--ink-soft);
        }
        .demo-flag {
          grid-column: 1 / -1;
          font-family: 'IBM Plex Mono', monospace;
          font-size: 11.5px;
          color: var(--steel);
          background: #fff;
          border: 1px dashed var(--steel-soft);
          padding: 6px 10px;
          border-radius: 3px;
          margin-bottom: 6px;
        }

        /* ---- LEFT: date index ---- */
        .index {
          background: var(--paper-raised);
          border: 1px solid var(--line);
          border-radius: 4px;
          padding: 10px;
          align-self: start;
        }
        .index__head {
          font-family: 'Oswald', sans-serif;
          text-transform: uppercase;
          letter-spacing: 0.08em;
          font-size: 12.5px;
          color: var(--ink-soft);
          padding: 6px 8px 10px;
          border-bottom: 2px solid var(--ink);
          margin-bottom: 8px;
        }
        .index__list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 6px; }

        .date-card {
          width: 100%;
          display: flex;
          align-items: center;
          gap: 10px;
          background: #fff;
          border: 1px solid var(--line);
          border-left: 4px solid var(--steel-soft);
          border-radius: 3px;
          padding: 8px 10px;
          cursor: pointer;
          text-align: left;
          transition: transform 0.12s ease, border-color 0.12s ease, box-shadow 0.12s ease;
          font-family: inherit;
        }
        .date-card:hover { transform: translateX(2px); box-shadow: 2px 2px 0 var(--line); }
        .date-card.is-active {
          border-left-color: var(--signal);
          box-shadow: 2px 2px 0 var(--ink);
          transform: translateX(2px);
        }
        .date-card.has-discrepancy { border-left-color: var(--red); }
        .date-card.is-active.has-discrepancy { border-left-color: var(--red); }

        .date-card__cal {
          font-family: 'IBM Plex Mono', monospace;
          text-align: center;
          line-height: 1;
          min-width: 40px;
        }
        .date-card__cal b { display: block; font-size: 18px; }
        .date-card__cal small { font-size: 10px; letter-spacing: 0.05em; color: var(--ink-soft); }

        .date-card__meta { flex: 1; min-width: 0; }
        .date-card__meta strong {
          display: block;
          font-size: 12.5px;
          font-weight: 600;
        }
        .date-card__meta span {
          font-family: 'IBM Plex Mono', monospace;
          font-size: 11px;
          color: var(--ink-soft);
        }

        /* ---- stamp ---- */
        .stamp { display: inline-flex; transform: rotate(-6deg); }
        .stamp__ring {
          display: flex;
          align-items: center;
          gap: 5px;
          border: 2px solid currentColor;
          border-radius: 999px;
          padding: 3px 9px 3px 7px;
          font-family: 'IBM Plex Mono', monospace;
          font-weight: 600;
        }
        .stamp--sm .stamp__ring { padding: 2px 7px 2px 6px; }
        .stamp__label { font-size: 10px; letter-spacing: 0.04em; white-space: nowrap; }
        .stamp--lg .stamp__label { font-size: 13px; }
        .stamp--clean { color: var(--green); }
        .stamp--flagged { color: var(--red); }
        .stamp--lg { animation: stamp-hit 0.28s ease-out; }
        @keyframes stamp-hit {
          0% { transform: rotate(-6deg) scale(1.5); opacity: 0; }
          70% { transform: rotate(-6deg) scale(0.95); opacity: 1; }
          100% { transform: rotate(-6deg) scale(1); }
        }

        /* ---- RIGHT: sheet ---- */
        .sheet {
          background: #fff;
          border: 1px solid var(--line);
          border-radius: 4px;
          min-height: 320px;
          display: flex;
          flex-direction: column;
        }
        .sheet__empty, .sheet__loading {
          margin: auto;
          text-align: center;
          color: var(--ink-soft);
          font-size: 13px;
          padding: 40px;
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 10px;
        }
        .sheet__loading svg { animation: spin 0.9s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .sheet__header {
          display: flex;
          align-items: flex-start;
          justify-content: space-between;
          gap: 16px;
          padding: 18px 20px;
          border-bottom: 3px double var(--ink);
        }
        .sheet__date-block { display: flex; align-items: baseline; gap: 10px; }
        .sheet__date-block h2 {
          font-family: 'Oswald', sans-serif;
          text-transform: uppercase;
          letter-spacing: 0.02em;
          font-size: 21px;
          margin: 0;
        }
        .sheet__date-block .weekday {
          font-family: 'IBM Plex Mono', monospace;
          font-size: 12px;
          color: var(--ink-soft);
        }
        .sheet__subline {
          font-family: 'IBM Plex Mono', monospace;
          font-size: 12px;
          color: var(--ink-soft);
          margin-top: 4px;
        }

        .sheet__table-wrap { overflow-x: auto; }
        table.ledger {
          width: 100%;
          border-collapse: collapse;
          font-family: 'IBM Plex Mono', monospace;
          font-size: 13px;
        }
        table.ledger thead th {
          text-align: left;
          text-transform: uppercase;
          letter-spacing: 0.05em;
          font-size: 10.5px;
          font-family: 'Inter', sans-serif;
          font-weight: 600;
          color: var(--ink-soft);
          padding: 10px 14px;
          border-bottom: 1px solid var(--ink);
          white-space: nowrap;
        }
        table.ledger td {
          padding: 9px 14px;
          border-bottom: 1px solid var(--line);
          white-space: nowrap;
        }
        table.ledger tbody tr.mismatch { background: var(--red-bg); }
        table.ledger tbody tr.mismatch td:first-child { border-left: 3px solid var(--red); }
        table.ledger tbody tr:not(.mismatch) td:first-child { border-left: 3px solid transparent; }
        table.ledger td.num { text-align: right; }
        td.notes { white-space: normal; color: var(--ink-soft); font-family: 'Inter', sans-serif; font-size: 12.5px; max-width: 240px; }

        .diff-pill {
          display: inline-flex;
          align-items: center;
          gap: 4px;
          font-weight: 600;
        }
        .diff-pill.zero { color: var(--green); }
        .diff-pill.off { color: var(--red); }

        .sheet__footer {
          margin-top: auto;
          display: flex;
          gap: 20px;
          padding: 12px 20px;
          border-top: 1px solid var(--line);
          font-family: 'IBM Plex Mono', monospace;
          font-size: 11.5px;
          color: var(--ink-soft);
        }
        .sheet__footer b { color: var(--ink); }
      `}</style>

      <div className="board__frame">
        <div className="board__title">
          <ClipboardList size={20} strokeWidth={2} />
          <h1>Log Stock Opname</h1>
          <span>omega vs fisik</span>
        </div>

        {usingDemoData && (
          <div className="demo-flag">
            Tidak bisa terhubung ke {API_URL} — menampilkan data contoh untuk pratinjau tampilan.
          </div>
        )}

        <nav className="index">
          <div className="index__head">Tanggal Tersedia</div>

          {loadingList && (
            <div className="sheet__loading" style={{ padding: '24px 8px' }}>
              <Loader2 size={18} />
              <span>Memuat daftar log...</span>
            </div>
          )}

          {!loadingList && dates.length === 0 && (
            <div className="sheet__empty" style={{ padding: '24px 8px' }}>
              <Inbox size={22} />
              <span>Belum ada log tercatat.</span>
            </div>
          )}

          {!loadingList && dates.length > 0 && (
            <ul className="index__list">
              {dates.map((d) => {
                const { day, month } = formatDate(d.recorded_on);
                const flagged = d.discrepancy_count > 0;
                return (
                  <li key={d.recorded_on}>
                    <button
                      type="button"
                      className={`date-card ${selectedDate === d.recorded_on ? 'is-active' : ''} ${flagged ? 'has-discrepancy' : ''}`}
                      onClick={() => handleSelect(d.recorded_on)}
                    >
                      <div className="date-card__cal">
                        <b>{day}</b>
                        <small>{month}</small>
                      </div>
                      <div className="date-card__meta">
                        <strong>{d.item_count} barang dicek</strong>
                        <span>{flagged ? `${d.discrepancy_count} selisih` : 'semua cocok'}</span>
                      </div>
                      <Stamp count={d.discrepancy_count} size="sm" />
                    </button>
                  </li>
                );
              })}
            </ul>
          )}
        </nav>

        <section className="sheet">
          {!selectedDate && (
            <div className="sheet__empty">
              <PackageSearch size={26} />
              <span>Pilih tanggal di sebelah kiri untuk melihat rincian stock opname.</span>
            </div>
          )}

          {selectedDate && loadingDetail && (
            <div className="sheet__loading">
              <Loader2 size={22} />
              <span>Memuat data {selectedDate}...</span>
            </div>
          )}

          {selectedDate && !loadingDetail && (
            <>
              <header className="sheet__header">
                <div>
                  <div className="sheet__date-block">
                    <h2>{formatDate(selectedDate).day} {formatDate(selectedDate).month} {formatDate(selectedDate).year}</h2>
                    <span className="weekday">{formatDate(selectedDate).weekday}</span>
                  </div>
                  <div className="sheet__subline">
                    {summary.total} entri &nbsp;·&nbsp; {summary.discrepancies} selisih &nbsp;·&nbsp; net {summary.net > 0 ? `+${summary.net}` : summary.net}
                  </div>
                </div>
                <Stamp count={selected ? selected.discrepancy_count : summary.discrepancies} size="lg" />
              </header>

              <div className="sheet__table-wrap">
                <table className="ledger">
                  <thead>
                    <tr>
                      <th>Kode Barang</th>
                      <th>Lokasi</th>
                      <th className="num">Fisik</th>
                      <th className="num">Omega</th>
                      <th className="num">Selisih</th>
                      <th>Catatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    {entries.map((row, i) => (
                      <tr key={`${row.barang}-${row.location}-${i}`} className={row.difference !== 0 ? 'mismatch' : ''}>
                        <td>{row.barang}</td>
                        <td>{row.location}</td>
                        <td className="num">{row.physical_stock}</td>
                        <td className="num">{row.omega_stock}</td>
                        <td className="num">
                          <span className={`diff-pill ${row.difference === 0 ? 'zero' : 'off'}`}>
                            {row.difference === 0 ? <Check size={12} strokeWidth={3} /> : <Flag size={12} strokeWidth={2.5} />}
                            {row.difference > 0 ? `+${row.difference}` : row.difference}
                          </span>
                        </td>
                        <td className="notes">{row.notes ?? '—'}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>

              <footer className="sheet__footer">
                <span>Total dicek: <b>{summary.total}</b></span>
                <span>Selisih ditemukan: <b>{summary.discrepancies}</b></span>
                <span>Net selisih: <b>{summary.net > 0 ? `+${summary.net}` : summary.net}</b></span>
              </footer>
            </>
          )}
        </section>
      </div>
    </div>
  );
};

export default OmegaLogList;