<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .summary {
            margin-bottom: 30px;
        }
        .task-list {
            width: 100%;
            border-collapse: collapse;
        }
        .task-list th, .task-list td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .task-list th {
            background-color: #f5f5f5;
        }
        .completed {
            text-decoration: line-through;
            color: #666;
        }
        .category {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Report</h1>
        <p>Generated on: {{ now()->format('F d, Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <p>Total Tasks: {{ $tasks->count() }}</p>
        <p>Completed Tasks: {{ $tasks->where('completed', true)->count() }}</p>
        <p>Pending Tasks: {{ $tasks->where('completed', false)->count() }}</p>
    </div>

    <h2>Task List</h2>
    <table class="task-list">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td class="{{ $task->completed ? 'completed' : '' }}">{{ $task->title }}</td>
                    <td>
                        @if($task->category)
                            <span class="category" style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}">
                                {{ $task->category->name }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $task->completed ? 'Completed' : 'Pending' }}</td>
                    <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 