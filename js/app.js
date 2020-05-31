var app = new Vue({
    el: '#app',
    data: {
        allTodos: [],
        tabs: [
            {text: 'All'},
            {text: 'Active'},
            {text: 'Completed'},
        ],
        activeTab: 'All'
    },
    mounted() {
        this.getAllTodos();
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
            for (var i = 0; i < this.allTodos.length; i++) {
                if (parseInt(this.allTodos[i].status) === 0) {
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
            if (todo.status == 0) {
                this.updateTask(todo.id, todo.name, 1);
                todo.status = 1;
            }
        },
        tabClick: function (tab) {
            this.activeTab = tab.text;
        },
        updateTodo: function (todo) {
            this.updateTask(todo.id, todo.name, todo.status);
        },
        getActiveTab: function () {
            for (let i = 0; i < this.tabs.length; i++) {
                if (this.tabs[i].active) {
                    return this.tabs[i].text;
                }
            }

            return 'All';
        },
        clearCompleted: function () {
            axios.get(this.buildUrl('delete_completed_task')).then((response) => {
                if (parseInt(response.data.code) === 200) {
                    for (let i = 0; i < this.allTodos.length; i++) {
                        if (parseInt(this.allTodos[i].status) === 1) {
                            this.allTodos.splice(i, 1);
                        }
                    }
                }
            }).catch(error => {
                console.log(error);
            });
        },
        filterTodos: function () {
            if (this.activeTab == 'All') return this.allTodos;
            let todos = [];
            for (let i = 0; i < this.allTodos.length; i++) {
                if ((parseInt(this.allTodos[i].status) === 0 && this.activeTab == 'Active') || (parseInt(this.allTodos[i].status) === 1 && this.activeTab == 'Completed')) {
                    todos.push(this.allTodos[i]);
                }
            }

            return todos;
        },
        storeTodo: function (name) {
            axios.post(this.buildUrl('create_task'), {
                name: name,
            }).then((response) => {
                let newTodo = response.data.data;
                newTodo.active = false;
                newTodo.edit = false;
                newTodo.status = parseInt(newTodo.status);
                this.allTodos.push(newTodo);
            }).catch(error => {
                console.log(error);
            });
        },
        getAllTodos: function () {
            axios.get(this.buildUrl()).then((response) => {
                let todos = response.data.data;
                for (let i = 0; i < todos.length; i++) {
                    todos[i].active = false;
                    todos[i].edit = false;
                    todos[i].status = parseInt(todos[i].status);
                }
                this.allTodos = todos;
            }).catch(error => {
                console.log(error);
            });
        },
        buildUrl: function (event = 'get_all_task', id = null) {
            let url = 'api.php?event=' + event;
            if (id) {
                url += '&id=' + id;
            }

            return url;
        },
        updateTask(id, name, status) {
            axios.post(this.buildUrl('update_task', id), {
                name: name,
                status: status,
                id: id
            }).then((response) => {
                return parseInt(response.data.code) === 200;
            }).catch(error => {
                return false
            });
        },
        deleteTask: function (todo) {
            axios.get(this.buildUrl('delete_task', todo.id)).then((response) => {
                if (parseInt(response.data.code) === 200) {
                    for (let i = 0; i < this.allTodos.length; i++) {
                        if (this.allTodos[i].id === todo.id) {
                            this.allTodos.splice(i, 1);
                        }
                    }
                }
            }).catch(error => {
                console.log(error);
            });
        }
    }
});