'use client';

import { useState, useRef, useEffect, KeyboardEvent } from 'react';

interface SearchableSelectProps {
  options: string[];
  value: string;
  placeholder?: string;
  onChange: (val: string, isSelection?: boolean) => void;
  allowCustom?: boolean;
}

export default function SearchableSelect({
  options,
  value,
  placeholder = 'Search...',
  onChange,
  allowCustom = true,
}: SearchableSelectProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [inputVal, setInputVal] = useState(value);
  const [highlighted, setHighlighted] = useState(-1);
  const containerRef = useRef<HTMLDivElement>(null);
  const itemRefs = useRef<(HTMLDivElement | null)[]>([]);

  // Sync external value
  useEffect(() => {
    setInputVal(value);
  }, [value]);

  const filtered = options.filter(opt =>
    opt.toLowerCase().includes(inputVal.trim().toLowerCase())
  );

  const open = () => { setIsOpen(true); setHighlighted(-1); };
  const close = () => { setIsOpen(false); setHighlighted(-1); };

  const handleSelect = (opt: string) => {
    setInputVal(opt);
    close();
    onChange(opt, true);
  };

  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        close();
      }
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, []);

  useEffect(() => {
    if (highlighted >= 0 && itemRefs.current[highlighted]) {
      itemRefs.current[highlighted]!.scrollIntoView({ block: 'nearest' });
    }
  }, [highlighted]);

  const handleKeyDown = (e: KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      if (!isOpen) open();
      setHighlighted(h => Math.min(h + 1, filtered.length - 1));
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      if (!isOpen) open();
      setHighlighted(h => Math.max(h - 1, 0));
    } else if (e.key === 'Enter') {
      if (isOpen && highlighted >= 0 && filtered[highlighted]) {
        e.preventDefault();
        handleSelect(filtered[highlighted]);
      }
    } else if (e.key === 'Escape') {
      close();
    }
  };

  return (
    <div
      className={`searchable-select-wrapper${isOpen ? ' open' : ''}`}
      ref={containerRef}
    >
      <div className="searchable-input-container">
        <input
          type="text"
          className="form-control searchable-input"
          placeholder={placeholder}
          value={inputVal}
          autoComplete="off"
          spellCheck={false}
          onFocus={open}
          onClick={() => { if (!isOpen) open(); }}
          onChange={e => {
            const v = e.target.value;
            setInputVal(v);
            if (!isOpen) open();
            onChange(v, false);
          }}
          onKeyDown={handleKeyDown}
        />
        <span className="searchable-arrow">▼</span>
      </div>
      {isOpen && (
        <div className="searchable-dropdown">
          {filtered.length === 0 ? (
            <div className="searchable-no-results">
              {allowCustom
                ? inputVal
                  ? `Use custom value: "${inputVal}"`
                  : 'Type or select an option...'
                : 'No matching options'}
            </div>
          ) : (
            filtered.map((opt, idx) => (
              <div
                key={opt}
                ref={el => { itemRefs.current[idx] = el; }}
                className={[
                  'searchable-option',
                  opt === inputVal ? 'selected' : '',
                  idx === highlighted ? 'highlighted' : '',
                ].filter(Boolean).join(' ')}
                onClick={() => handleSelect(opt)}
                onMouseEnter={() => setHighlighted(idx)}
              >
                {opt}
              </div>
            ))
          )}
        </div>
      )}
    </div>
  );
}
