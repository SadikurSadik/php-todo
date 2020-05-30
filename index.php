<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDo</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
<div id="app">
    <h1 id="title" class="text-center">todos</h1>

    <div id="todo-wrapper">
        <div class="todo-input">
            <input id="name" type="text" placeholder="What need to be done?">
        </div>
        <div v-show="showTodoDetails()" class="todo-list">
            <ul>
                <li @mouseover="mousehover(todo)" @mouseleave="mouseleave(todo)" v-for="todo in todos">
                    <img height="18" v-on:click="completeTask(todo)" v-bind:src="itemCircleImageSrc(todo)" alt="Circle">
                    <span v-show="todo.edit == false" @dblclick = "todo.edit = true">{{ todo.name }}</span>
                    <input v-show = "todo.edit == true" v-model = "todo.name"
                           v-on:blur= "todo.edit=false; $emit('update')"
                           @keyup.enter = "todo.edit=false; $emit('update'); updateTodo(todo)">
                    <a v-show="todo.active" class="f-right actionBtn" href="#">x</a>
                </li>
            </ul>
        </div>
        <div v-show="showTodoDetails()" class="todo-footer">
            <div class="f-left" style="width: 140px; color: #C0C0BE;">
                {{ pendingItemText() }}
            </div>
            <div id="btn-group" class="f-left text-center" style="width: 198px;">
                <a v-for="tab in tabs" v-on:click="tabClick(tab)" v-bind:class="{ active: tab.active }" class="actionBtn " href="#">{{ tab.text }}</a>
            </div>
            <div class="f-right" style="width: 140px;">
                <a class="actionBtn" v-on:click="clearCompleted" id="clearCompleted" href="#">Clear Completed</a>
            </div>
        </div>
    </div>
</div>

<script src="js/vue.js"></script>
<script src="js/app.js"></script>
</body>
</html>