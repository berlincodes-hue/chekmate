<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TaskController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task', [
            'except' => ['report']
        ]);
    }

    public function index(Request $request)
    {
        $query = $request->user()->tasks()->with('category');

        // Handle search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Handle status filter
        if ($request->has('status')) {
            if ($request->status === 'completed') {
                $query->where('completed', true);
            } elseif ($request->status === 'pending') {
                $query->where('completed', false);
            }
        }

        // Handle category filter
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Handle due date filter
        if ($request->has('due_date')) {
            switch ($request->due_date) {
                case 'today':
                    $query->whereDate('due_date', today());
                    break;
                case 'tomorrow':
                    $query->whereDate('due_date', today()->addDay());
                    break;
                case 'this_week':
                    $query->whereBetween('due_date', [today(), today()->endOfWeek()]);
                    break;
            }
        }

        $tasks = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(10);
        $categories = $request->user()->categories;

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = auth()->user()->categories;
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $categories = auth()->user()->categories;
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function toggleComplete(Task $task)
    {
        $this->authorize('update', $task);
        $task->update(['completed' => !$task->completed]);
        return redirect()->back()->with('success', 'Task status updated!');
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['tasks'] as $taskData) {
            $task = Task::find($taskData['id']);
            if ($task && $task->user_id === auth()->id()) {
                $task->update(['order' => $taskData['order']]);
            }
        }

        return response()->json(['message' => 'Task order updated successfully']);
    }

    public function report()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $tasks = $user->tasks()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pdf = PDF::loadView('tasks.report', compact('tasks'));
        
        return $pdf->download('task-report.pdf');
    }

    public function createTestTask()
    {
        $task = Task::create([
            'title' => 'Test Task for Email Reminder',
            'description' => 'This is a test task to verify email reminder functionality',
            'due_date' => now()->addDays(2),
            'priority' => 'high',
            'user_id' => Auth::id(),
            'category_id' => Category::first()->id ?? null,
            'completed' => false
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Test task created successfully. You should receive an email reminder in 2 days.');
    }
} 