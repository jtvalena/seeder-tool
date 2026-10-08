import type { NextConfig } from 'next';

const config: NextConfig = {
  // Include PHP reference files in Vercel's output bundle
  outputFileTracingIncludes: {
    '/': ['./seeder-tool/references/**/*.php'],
  },
};

export default config;
