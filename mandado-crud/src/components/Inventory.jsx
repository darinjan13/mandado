import React, { useEffect, useState } from 'react';
import axios from 'axios';

const API = 'http://localhost:8000';

export default function Inventory() {
    const [items, setItems] = useState([]);
    const [form, setForm] = useState({ name: '', quantity: '', price: '' });
    const [errors, setErrors] = useState({});
    const [editingId, setEditingId] = useState(null);

    const fetchItems = async () => {
        const res = await axios.get(`${API}/items`);
        setItems(res.data);
        console.log(items.length);

    };

    const validate = () => {
        const newErrors = {};
        if (!form.name.trim()) newErrors.name = 'Item name is required.';
        if (!form.quantity || isNaN(form.quantity) || parseInt(form.quantity) <= 0) {
            newErrors.quantity = 'Quantity must be a positive number.';
        }
        if (!form.price || isNaN(form.price) || parseFloat(form.price) <= 0) {
            newErrors.price = 'Price must be a positive number.';
        }
        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!validate()) return;

        if (editingId) {
            await axios.put(`${API}/items/${editingId}`, form);
        } else {
            await axios.post(`${API}/items`, form);
        }
        setForm({ name: '', quantity: '', price: '' });
        setEditingId(null);
        setErrors({});
        fetchItems();
    };

    const handleEdit = (item) => {
        setForm({ name: item.name, quantity: item.quantity, price: item.price });
        setEditingId(item.id);
    };

    const handleDelete = async (id) => {
        const confirmDelete = window.confirm("Are you sure you want to delete this item?");
        if (!confirmDelete) return;

        await axios.delete(`${API}/items/${id}`);
        fetchItems();
    };

    useEffect(() => {
        fetchItems();
    }, []);

    return (
        <div className="max-w-xl mx-auto mt-10 p-6 bg-white shadow rounded">
            <h2 className="text-2xl font-bold mb-4 text-center">📦 Inventory System</h2>

            <form onSubmit={handleSubmit} className="grid sm:grid-cols-4 gap-4 mb-8">
                <div>
                    <input
                        type="text"
                        placeholder="Item name"
                        className="border border-gray-300 p-2 rounded-md w-full"
                        value={form.name}
                        onChange={(e) => setForm({ ...form, name: e.target.value })}
                    />
                    {errors.name && <p className="text-red-500 text-xs">{errors.name}</p>}
                </div>
                <div>
                    <input
                        type="number"
                        placeholder="Quantity"
                        className="border border-gray-300 p-2 rounded-md w-full"
                        value={form.quantity}
                        onChange={(e) => setForm({ ...form, quantity: e.target.value })}
                    />
                    {errors.quantity && <p className="text-red-500 text-xs">{errors.quantity}</p>}
                </div>
                <div>
                    <input
                        type="number"
                        step="0.01"
                        placeholder="Price"
                        className="border border-gray-300 p-2 rounded-md w-full"
                        value={form.price}
                        onChange={(e) => setForm({ ...form, price: e.target.value })}
                    />
                    {errors.price && <p className="text-red-500 text-xs">{errors.price}</p>}
                </div>
                <button
                    type="submit"
                    className="bg-blue-600 hover:bg-blue-700 text-white rounded-md px-4 py-2 transition w-full"
                >
                    {editingId ? 'Update' : 'Add Item'}
                </button>
            </form>

            <ul className="space-y-4">
                {items.length > 0 ? items.map((item) => (
                    <li
                        key={item.id}
                        className="flex justify-between items-center bg-gray-50 p-4 rounded-lg border shadow-sm"
                    >
                        <div>
                            <p className="font-medium text-lg">{item.name}</p>
                            <p className="text-sm text-gray-500">
                                {item.quantity} pcs • ₱{item.price}
                            </p>
                        </div>
                        <div className="space-x-2">
                            <button
                                onClick={() => handleEdit(item)}
                                className="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md"
                            >
                                Edit
                            </button>
                            <button
                                onClick={() => handleDelete(item.id)}
                                className="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md"
                            >
                                Delete
                            </button>
                        </div>
                    </li>
                )) : (
                    <>
                        <h1>No records found.</h1>
                    </>
                )}
            </ul>
        </div>
    );
}
