var app = new Vue({
    el: '#app',
    data: {
        allTodos: [
            {id: 1, name: 'Item 1', status: 1, active: false, edit: false},
            {id: 2, name: 'Item 2', status: 0, active: false, edit: false},
            {id: 3, name: 'Item 3', status: 1, active: false, edit: false}
        ],
        todos: [
            {id: 1, name: 'Item 1', status: 1, active: false, edit: false},
            {id: 2, name: 'Item 2', status: 0, active: false, edit: false},
            {id: 3, name: 'Item 3', status: 1, active: false, edit: false}
        ],
        tabs: [
            {text: 'All', active: true},
            {text: 'Active', active: false},
            {text: 'Completed', active: false},
        ]
    },
    methods: {
        mousehover: function (item) {
            item.active = true;
        },
        mouseleave: function (item) {
            item.active = false;
        },
        showTodoDetails: function () {
            return this.allTodos.length > 0;
        },
        pendingItemText: function () {
            let count = 0;
            let text = '';
            for (var i=0; i < this.todos.length; i++) {
                if(this.todos[i].status == 0) {
                    count++;
                }
            }
            text += count.toString() + (count > 1 ? ' items' : ' item') + ' left';

            return text;
        },
        itemCircleImageSrc: function (item) {
            return item.status ? 'images/check-circle-regular.svg' : 'images/circle-regular.svg';
        },
        completeTask: function (todo) {
            if(todo.status == 0) {
                todo.status = 1;
                // TODO:: api request for task update
            }
        },
        tabClick: function (tab) {
            for(let i = 0; i < this.tabs.length; i++) {
                if(this.tabs[i].text === tab.text) {
                    this.tabs[i].active = true;
                } else {
                    this.tabs[i].active = false;
                }
            }

            this.showTodos(tab.text);
        },
        showTodos: function (tabName = 'All') {
            this.todos = [];
            for (let i = 0; i < this.allTodos.length; i++) {
                if(tabName == 'All' || (this.allTodos[i].status == 0 && tabName == 'Active') || (this.allTodos[i].status == 1 && tabName == 'Completed')) {
                    this.todos.push(this.allTodos[i]);
                }
            }
        },
        updateTodo: function (todo) {
            // TODO::update task via Api call
        },
        getActiveTab: function() {
            for (let i=0; i<this.tabs.length; i++) {
                if(this.tabs[i].active) {
                    return this.tabs[i].text;
                }
            }

            return 'All';
        },
        clearCompleted: function () {
            for (let i = 0; i < this.allTodos.length; i++) {
                if(this.allTodos[i].status == 1) {
                    this.allTodos.splice(i, 1);
                }
            }
            this.showTodos(this.getActiveTab());
            // TODO::clear all task via api call
        }
    }
});