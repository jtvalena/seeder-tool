import type { Metadata } from 'next';
import './globals.css';

export const metadata: Metadata = {
  title: 'Seeder AutoFill Tool',
  description: 'Generate seeder run scripts for BulkUpdateFromText — powered by Vinext + Cloudflare Workers',
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en">
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link
          href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet"
        />
      </head>
      <body className="dark-mode">{children}</body>
    </html>
  );
}
