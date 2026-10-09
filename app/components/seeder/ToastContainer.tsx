import { Toast } from './types';

interface ToastContainerProps {
  toasts: Toast[];
}

// ─────────────────────────────────────────────
// ToastContainer — fixed bottom-right notification list
// ─────────────────────────────────────────────
export default function ToastContainer({ toasts }: ToastContainerProps) {
  return (
    <div className="toast-container">
      {toasts.map(t => (
        <div key={t.id} className={`toast ${t.type}`}>
          <span>{t.message}</span>
        </div>
      ))}
    </div>
  );
}
