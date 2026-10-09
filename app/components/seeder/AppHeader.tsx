import { Code, Moon, Sparkles, Sprout, Sun } from 'lucide-react';

interface AppHeaderProps {
  theme: 'dark' | 'light';
  onToggleTheme: () => void;
  onOpenModels: () => void;
}

// ─────────────────────────────────────────────
// AppHeader — brand bar + theme toggle + models button
// ─────────────────────────────────────────────
export default function AppHeader({ theme, onToggleTheme, onOpenModels }: AppHeaderProps) {
  return (
    <header className="app-header">
      <div className="brand">
        <div className="logo-icon"> <Code size={18} className='' /> </div>
        <div className="brand-text">
          <h1>Seeder Tool</h1>
          {/* <span className="badge">v2.0</span> */}
        </div>
      </div>

      <div className="header-actions">
        <button className="btn btn-secondary" onClick={onOpenModels}>
          Models &amp; Columns
        </button>
        <button
          className="btn btn-icon"
          onClick={onToggleTheme}
          title="Toggle theme"
        >
          {theme === 'dark' ? <Sun size={16} /> : <Moon size={16} />}
        </button>
      </div>
    </header>
  );
}
