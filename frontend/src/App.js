import React, { useState, useEffect } from 'react';

// URL de l'API backend
const API_URL = 'http://192.168.10.132:8080/api';

function App() {
  const [todos, setTodos] = useState([]);
  const [newTodo, setNewTodo] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Charger les tâches au démarrage
  useEffect(() => {
    fetchTodos();
  }, []);

  const fetchTodos = async () => {
    try {
      setLoading(true);
      const response = await fetch(`${API_URL}/todos`);
      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.error || 'Erreur lors du chargement');
      }

      // Sécurité : on s'assure que data est bien un tableau avant de faire un .map() plus tard
      setTodos(Array.isArray(data) ? data : []);
      setError(null);
    } catch (err) {
      setError(err.message || 'Impossible de charger les tâches.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };
  // ---------------------------------------

  const addTodo = async (e) => {
    e.preventDefault();
    if (!newTodo.trim()) return;

    try {
      const response = await fetch(`${API_URL}/todos`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title: newTodo }),
      });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || 'Erreur lors de l\'ajout');
      
      setTodos([data, ...todos]);
      setNewTodo('');
      setError(null);
    } catch (err) {
      setError(err.message);
    }
  };

  const toggleTodo = async (id, completed) => {
    try {
      const response = await fetch(`${API_URL}/todos/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ completed: !completed }),
      });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || 'Erreur mise à jour');
      
      setTodos(todos.map(todo => todo.id === id ? data : todo));
      setError(null);
    } catch (err) {
      setError(err.message);
    }
  };

  const deleteTodo = async (id) => {
    try {
      const response = await fetch(`${API_URL}/todos/${id}`, {
        method: 'DELETE',
      });
      if (!response.ok) throw new Error('Erreur lors de la suppression');
      
      setTodos(todos.filter(todo => todo.id !== id));
      setError(null);
    } catch (err) {
      setError(err.message);
    }
  };

  const totalTodos = todos.length;
  const completedTodos = todos.filter(todo => todo.completed).length;

  return (
    <div className="container">
      <h1>📝 Ma Todo List</h1>

      <form onSubmit={addTodo} className="input-container">
        <input
          type="text"
          placeholder="Ajouter une nouvelle tâche..."
          value={newTodo}
          onChange={(e) => setNewTodo(e.target.value)}
        />
        <button type="submit">Ajouter</button>
      </form>

      {error && <div className="error">{error}</div>}

      {loading && <div className="loading">Chargement des tâches...</div>}

      {!loading && (
        <>
          {todos.length === 0 ? (
            <div className="empty-state">
              Aucune tâche pour le moment. Ajoutez-en une ! 🎉
            </div>
          ) : (
            <ul className="todo-list">
              {todos.map(todo => (
                <li 
                  key={todo.id} 
                  className={`todo-item ${todo.completed ? 'completed' : ''}`}
                >
                  <input
                    type="checkbox"
                    className="todo-checkbox"
                    checked={todo.completed}
                    onChange={() => toggleTodo(todo.id, todo.completed)}
                  />
                  <span className="todo-text">{todo.title}</span>
                  <button 
                    className="delete-btn" 
                    onClick={() => deleteTodo(todo.id)}
                  >
                    Supprimer
                  </button>
                </li>
              ))}
            </ul>
          )}

          {todos.length > 0 && (
            <div className="stats">
              Total: {totalTodos} | Complétées: {completedTodos} | Restantes: {totalTodos - completedTodos}
            </div>
          )}
        </>
      )}
    </div>
  );
}

export default App;
