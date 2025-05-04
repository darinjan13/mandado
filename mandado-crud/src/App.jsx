import React from 'react';
import Inventory from './components/Inventory';

function App() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-100 to-blue-100 flex items-center justify-center p-4">
      <div className="bg-white w-full max-w-3xl rounded-2xl shadow-2xl border border-gray-200 p-8">
        {/* Header */}
        <header className="text-center mb-8">
          <h1 className="text-4xl font-bold text-blue-700 flex items-center justify-center gap-3">
            📦 <span>Inventory Manager</span>
          </h1>
          <p className="text-gray-500 text-sm mt-1">Track your stock efficiently with ease</p>
        </header>

        {/* Main Content */}
        <main>
          <Inventory />
        </main>

      </div>
    </div>
  );
}

export default App;
