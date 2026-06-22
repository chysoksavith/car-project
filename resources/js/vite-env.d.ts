/// <reference types="vite/client" />

declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<{}, {}, any>
  export default component
}

// Ziggy global route() helper
import type { Config, Router } from 'ziggy-js';
declare global {
  // # Route
  function route(name?: string, params?: any, absolute?: boolean, config?: Config): string;
}
