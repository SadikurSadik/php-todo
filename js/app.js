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
            this.allTodos.map(function (todo) {
                if (parseInt(todo.status) === 0) {
                    count++;
                }
            });
            text += count.toString() + (count > 1 ? ' items' : ' item') + ' left';

            return text;
        },
        itemCircleImageSrc: function (item) {
            return item.status ? 'images/check-circle-regular.svg' : 'images/circle-regular.svg';
        },
        completeTask: function (todo) {
            if (parseInt(todo.status) === 0) {
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
        clearCompleted: function () {
            axios.get(this.buildUrl('delete_completed_task')).then((response) => {
                if (parseInt(response.data.code) === 200) {
                    let $this = this;
                    this.allTodos.map(function (todo, key) {
                        if (parseInt(todo.status) === 1) {
                            $this.allTodos.splice(key, 1);
                        }
                    });
                }
            }).catch(error => {
                console.log(error);
            });
        },
        filterTodos: function () {
            if (this.activeTab === 'All') return this.allTodos;
            let todoList = [];
            let $this = this;
            this.allTodos.map(function (todo) {
                if ((parseInt(todo.status) === 0 && $this.activeTab === 'Active') || (parseInt(todo.status) === 1 && $this.activeTab === 'Completed')) {
                    todoList.push(todo);
                }
            });

            return todoList;
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
                let todoList = response.data.data;
                todoList.map(function (todo) {
                    todo.active = false;
                    todo.edit = false;
                    todo.status = parseInt(todo.status);
                });
                this.allTodos = todoList;
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
            this.apiRequest(this.buildUrl('update_task', id), 'post', {
                name: name,
                status: status,
                id: id
            }).then((response) => {
                if (parseInt(response.data.code) === 200) {

                }
            }, (error) => {
                console.log(error);
            });
        },
        deleteTask: function (todo) {
            this.apiRequest(this.buildUrl('delete_task', todo.id)).then((response) => {
                if (parseInt(response.data.code) === 200) {
                    let $this = this;
                    this.allTodos.map(function (item, key) {
                        if (item.id === todo.id) {
                            $this.allTodos.splice(key, 1);
                        }
                    });
                }
            }, (error) => {
                console.log(error);
            });
        },
        apiRequest: function (url, method = 'get', data = {}) {
            return axios({
                method: method,
                url: url,
                data: data
            });
        }
    }
});