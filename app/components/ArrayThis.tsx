'use client';

import { useState, useRef, useCallback, useEffect } from 'react';
import { Copy, Trash2, ArrowRightLeft } from 'lucide-react';

// ─────────────────────────────────────────────
// ArrayThis — converts a newline-separated list to a comma-separated array string
// ─────────────────────────────────────────────
export default function ArrayThis() {
  const [input, setInput]           = useState('');
  const [output, setOutput]         = useState('');
  const [withQuotes, setWithQuotes] = useState(true);
  const [copied, setCopied]         = useState(false);
  const outputRef   = useRef<HTMLTextAreaElement>(null);
  const textareaRef = useRef<HTMLTextAreaElement>(null);
  const gutterRef   = useRef<HTMLDivElement>(null);

  // ── Keep gutter scroll in sync with textarea scroll ──────
  const syncScroll = useCallback(() => {
    if (textareaRef.current && gutterRef.current) {
      gutterRef.current.scrollTop = textareaRef.current.scrollTop;
    }
  }, []);

  useEffect(() => {
    const ta = textareaRef.current;
    if (!ta) return;
    ta.addEventListener('scroll', syncScroll, { passive: true });
    return () => ta.removeEventListener('scroll', syncScroll);
  }, [syncScroll]);

  // ── Derived line count ────────────────────────────────────
  const lineCount = input === '' ? 1 : input.split('\n').length;

  // ── Handlers ─────────────────────────────────────────────
  const handleConvert = useCallback(() => {
    const lines = input
      .split('\n')
      .map(l => l.trim())
      .filter(Boolean);

    if (lines.length === 0) {
      setOutput('');
      return;
    }

    const result = withQuotes
      ? lines.map(l => `'${l}'`).join(', ')
      : lines.join(', ');

    setOutput(result);

    setTimeout(() => {
      if (outputRef.current) outputRef.current.scrollTop = 0;
    }, 30);
  }, [input, withQuotes]);

  const handleCopy = useCallback(() => {
    if (!output) return;
    const write = (text: string) => {
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).catch(() => {});
      } else {
        const ta = document.createElement('textarea');
        ta.value = text;
        Object.assign(ta.style, { position: 'fixed', opacity: '0' });
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
      }
    };
    write(output);
    setCopied(true);
    setTimeout(() => setCopied(false), 1500);
  }, [output]);

  const handleClearAll = useCallback(() => {
    setInput('');
    setOutput('');
  }, []);

  const handleKeyDown = useCallback((e: React.KeyboardEvent) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
      e.preventDefault();
      handleConvert();
    }
  }, [handleConvert]);

  return (
    <div className="arraythis-workspace">

      {/* ── LEFT: Input ────────────────────────────────── */}
      <section className="panel arraythis-panel">
        <div className="panel-header">
          <div className="panel-title"><h2>Input List</h2></div>
        </div>
        <div className="arraythis-body">
          <div className="arraythis-editor">
            {/* Line-number gutter */}
            <div className="arraythis-gutter" ref={gutterRef} aria-hidden="true">
              {Array.from({ length: lineCount }, (_, i) => (
                <span key={i} className="arraythis-line-num">{i + 1}</span>
              ))}
            </div>
            <textarea
              ref={textareaRef}
              className="arraythis-textarea outline-none active:outline-none"
              value={input}
              onChange={e => setInput(e.target.value)}
              onKeyDown={handleKeyDown}
              spellCheck={false}
              autoFocus
            />
          </div>
        </div>
      </section>

      {/* ── MIDDLE: Controls ───────────────────────────── */}
      <div className="arraythis-controls">
        <button className="btn btn-primary btn-convert" onClick={handleConvert}>
          <ArrowRightLeft size={16} />
          Convert
        </button>
        <span className="arraythis-controls-label">With Quotes</span>

        <div className="arraythis-quote-toggle">
          <button
            type="button"
            className="quote-btn"
            style={withQuotes ? { background: '#3b82f6', borderColor: '#3b82f6', color: '#fff', fontWeight: 700 } : {}}
            onClick={() => setWithQuotes(true)}
          >
            Yes
          </button>
          <button
            type="button"
            className="quote-btn"
            style={!withQuotes ? { background: '#3b82f6', borderColor: '#3b82f6', color: '#fff', fontWeight: 700 } : {}}
            onClick={() => setWithQuotes(false)}
          >
            No
          </button>
        </div>

        <button className="btn btn-sm btn-danger" onClick={handleClearAll}>
          <Trash2 size={14} /> Clear All
        </button>
      </div>

      {/* ── RIGHT: Output ──────────────────────────────── */}
      <section className="panel arraythis-panel">
        <div className="panel-header">
          <div className="panel-title"><h2>Output</h2></div>
          <div className="panel-actions">
            <button
              className="btn btn-sm btn-primary"
              onClick={handleCopy}
              disabled={!output}
            >
              <Copy size={14} />
              {copied ? 'Copied!' : 'Copy'}
            </button>
          </div>
        </div>
        <div className="arraythis-body">
          <textarea
            ref={outputRef}
            className="arraythis-textarea arraythis-output"
            placeholder="Converted output will appear here..."
            value={output}
            onChange={e => setOutput(e.target.value)}
            spellCheck={false}
          />
        </div>
      </section>

    </div>
  );
}
