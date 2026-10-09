'use client';

import { useSeederTool }  from './seeder/useSeederTool';
import AppHeader          from './seeder/AppHeader';
import GeneratorPanel     from './seeder/GeneratorPanel';
import NotepadPanel       from './seeder/NotepadPanel';
import ToastContainer     from './seeder/ToastContainer';
import ModelsModal        from './seeder/modals/ModelsModal';

// ─────────────────────────────────────────────
// SeederTool — root orchestrator
// Wires the hook to UI components; contains no logic of its own.
// ─────────────────────────────────────────────
export default function SeederTool({
  initialModels,
  modelCount,
}: {
  initialModels: Record<string, string[]>;
  modelCount: number;
}) {
  const {
    // theme
    theme, setTheme,
    // company
    company, setCompany, customCompany, setCustomCompany,
    // form
    seederType, handleSeederTypeChange,
    fields, setField, handleModelChange,
    handleGenerate, handleResetForm,
    // models
    modelsData, modelNames, columnOptions,
    isModelsModalOpen, openModelsModal, closeModelsModal,
    handleSaveModels, handleResetToDefaults,
    // notepad
    notepad, setNotepad, notepadRef,
    historyStack,
    handleCopyAll, handleDownload, handleUndo, handleClear,
    // csv
    csvImportRef, handleCsvFileChange,
    // toasts
    toasts, showToast,
  } = useSeederTool(initialModels, modelCount);

  return (
    <div className="app-container">
      <AppHeader
        theme={theme}
        onToggleTheme={() => setTheme(t => t === 'dark' ? 'light' : 'dark')}
        onOpenModels={openModelsModal}
      />

      <main className="workspace">
        <GeneratorPanel
          company={company}
          setCompany={setCompany}
          customCompany={customCompany}
          setCustomCompany={setCustomCompany}
          seederType={seederType}
          onSeederTypeChange={handleSeederTypeChange}
          fields={fields}
          setField={setField}
          handleModelChange={handleModelChange}
          modelNames={modelNames}
          columnOptions={columnOptions}
          modelCount={modelCount}
          onGenerate={handleGenerate}
          onReset={handleResetForm}
        />

        <NotepadPanel
          notepad={notepad}
          setNotepad={setNotepad}
          notepadRef={notepadRef}
          historyStack={historyStack}
          onCopy={handleCopyAll}
          onDownload={handleDownload}
          onUndo={handleUndo}
          onClear={handleClear}
          csvImportRef={csvImportRef}
          onCsvImport={handleCsvFileChange}
        />
      </main>

      {isModelsModalOpen && (
        <ModelsModal
          initialSnapshot={modelsData}
          defaultSnapshot={initialModels}
          modelCount={modelCount}
          onSave={handleSaveModels}
          onClose={closeModelsModal}
          onResetToDefaults={handleResetToDefaults}
          showToast={showToast}
        />
      )}

      <ToastContainer toasts={toasts} />
    </div>
  );
}
