import InputTag from 'vue-input-tag';

var saveData = (function () {
    var a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    return function (data, fileName) {
        var blob = new Blob([data], {type: "data:application/octet-stream"}),
            url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = fileName;
        a.click();
        window.URL.revokeObjectURL(url);
    };
}());

var phpplugins = Vue.component('phpplugins', {
    components: { InputTag },
    template: '#phpplugins-template',
    methods: {
        tags_changed: function() {
            this.$emit('tags_changed', arguments);
        }
    },
    data: function () {
        return {
            tags: this.php_extensions,
            placeholder: 'Add Extension'
        }
    },
    props: ['php_extensions']
});

Vue.component('mountpoint', {
    props: ["mountpoint"],
    template: '#mountpoint-template',
    methods: {
        remove: function() {
            this.$emit('remove');
        }
    }
});

Vue.component('portset', {
    props: ['portset', 'webserver_ports'],
    template: '#port-set-template',
    methods: {
        remove_port: function() {
            this.$emit('removeport')
        }
    }
});

Vue.component('envvar', {
    props: ['variable'],
    template: '#environment-variable-template',
    methods: {
        remove: function() {
            this.$emit('remove')
        }
    }
});

var app = new Vue({
    el: "#content",
    components: {
        phpplugins: phpplugins
    },
    data: {
        cb_laravel_artisan: '',
        cb_symfony_4_console: '',
        cache: '',
        cli: 'checked',
        composer: 'checked',
        composer_official: true,
        database_mongodb: false,
        database_mongodb_version: '3.7',
        database_mysql: false,
        database_mysql_version: '5.7',
        database_envvars: [
            {id: 0, name: 'MYSQL_ROOT_PASSWORD', value: 'rootpassword'},
            {id: 1, name: 'MYSQL_USER', value: 'dbuser'},
            {id: 2, name: 'MYSQL_PASSWORD', value: 'dbuser'},
            {id: 3, name: 'MYSQL_DATABASE', value: 'appdb'}
        ],
        database_envvar_id: 4,
        mailhog: '',
        nodejs_mountpoints: [{id: 0, localPath: './', containerPath: '/var/www/'}],
        nodejs_mountpoints_id: 1,
        nodejs_ports: [],
        nodejs_ports_id: 0,
        nodejs_version: '',
        python_mountpoints: [{id: 0, localPath: './', containerPath: '/app/'}],
        python_mountpoints_id: 1,
        python_ports: [],
        python_ports_id: 0,
        python_version: '',
        php_extensions: ['mbstring', 'zip', 'xdebug', 'gd', 'intl', 'xml', 'curl', 'json', 'pdo', 'pdo_mysql'],
        php_version: '',
        php_webserver: '',
        queue: '',
        webserver: 'apache',
        webserver_docroot: '/var/www/html',
        webserver_ports: [{id: 0, srcPort: 80, hostPort: 8080}],
        webserver_ports_id: 1,
        webserver_mountpoints: [{id: 0, localPath: './', containerPath: '/var/www/'}],
        webserver_mountpoints_id: 1
    },
    methods: {
        add_database_envvar: function() {
            this.database_envvars.push({id: this.database_envvar_id, name: '', value: ''});
            this.database_envvar_id += 1;
        },
        add_nodejs_port: function() {
            this.nodejs_ports.push({id: this.nodejs_ports_id, srcPort: '', hostPort: ''});
            this.nodejs_ports_id += 1;
        },
        add_nodejs_mountpoint: function() {
            this.nodejs_mountpoints.push({id: this.nodejs_mountpoints_id, localPath: "", containerPath: ''});
            this.nodejs_mountpoints_id += 1;
        },
        add_python_port: function() {
            this.python_ports.push({id: this.python_ports_id, srcPort: '', hostPort: ''});
            this.python_ports_id += 1;
        },
        add_python_mountpoint: function() {
            this.python_mountpoints.push({id: this.python_mountpoints_id, localPath: "", containerPath: ''});
            this.python_mountpoints_id += 1;
        },
        add_webserver_port: function() {
            this.webserver_ports.push({id: this.webserver_ports_id, srcPort: '', hostPort: ''});
            this.webserver_ports_id += 1;
        },
        add_webserver_mountpoint: function() {
            this.webserver_mountpoints.push({id: this.webserver_mountpoints_id, localPath: "", containerPath: ''});
            this.webserver_mountpoints_id += 1;
        },
        php_ext_changed: function(extensions, caller) {
            this.php_extensions = extensions[0];
        },
        php_version_changed: function(event, caller) {
            if (this.php_version > 7.0) {
                this.composer_official = true;
            } else {
                this.composer_official = false;
            }
        },
        remove_database_envvar: function(id) {
            for(var index in this.database_envvars) {
                if (id === this.database_envvars[index].id) {
                    this.database_envvars.splice(index, 1);
                }
            }
        },
        remove_nodejs_mountpoint: function(id) {
            for(var index in this.nodejs_mountpoints) {
                if (id === this.nodejs_mountpoints[index].id) {
                    this.nodejs_mountpoints.splice(index, 1);
                }
            }
        },
        remove_nodejs_port: function(id) {
            for(var portset in this.nodejs_ports) {
                if (id === this.nodejs_ports[portset].id) {
                    this.nodejs_ports.splice(portset, 1);
                }
            }
        },
        remove_python_mountpoint: function(id) {
            for(var index in this.python_mountpoints) {
                if (id === this.python_mountpoints[index].id) {
                    this.python_mountpoints.splice(index, 1);
                }
            }
        },
        remove_python_port: function(id) {
            for(var portset in this.python_ports) {
                if (id === this.python_ports[portset].id) {
                    this.python_ports.splice(portset, 1);
                }
            }
        },
        remove_webserver_mountpoint: function(id) {
            for(var index in this.webserver_mountpoints) {
              if (id === this.webserver_mountpoints[index].id) {
                  this.webserver_mountpoints.splice(index, 1);
              }
            }
        },
        remove_webserver_port: function(id) {
            for(var portset in this.webserver_ports) {
              if (id === this.webserver_ports[portset].id) {
                  this.webserver_ports.splice(portset, 1);
              }
            }
        },
        submit_form: function() {
            axios({
                method: 'post',
                url: '/app.php', 
                responseType: 'arraybuffer',
                data: {
                    cb_laravel_artisan: this.cb_laravel_artisan,
                    cb_symfony_4_console: this.cb_symfony_4_console,
                    cache: this.cache,
                    composer: this.composer,
                    composer_official: this.composer_official,
                    cli: this.cli,
                    database: this.database,
                    database_mongodb: this.database_mongodb,
                    database_mongodb_version: this.database_mongodb_version,
                    database_mysql: this.database_mysql,
                    database_mysql_version: this.database_mysql_version,
                    database_envvars: this.database_envvars,
                    database_version: this.database_version,
                    mailhog: this.mailhog,
                    nodejs_mountpoints: this.nodejs_mountpoints,
                    nodejs_ports: this.nodejs_ports,
                    nodejs_version: this.nodejs_version,
                    php_extensions: this.php_extensions,
                    php_version: this.php_version,
                    php_webserver: this.php_webserver,
                    python_mountpoints: this.python_mountpoints,
                    python_ports: this.python_ports,
                    python_version: this.python_version,
                    queue: this.queue,
                    webserver: this.webserver,
                    webserver_docroot: this.webserver_docroot,
                    webserver_mountpoints: this.webserver_mountpoints,
                    webserver_ports: this.webserver_ports
                }
            }).then(function(response) {
                saveData(response.data, 'container-builder.zip');
            }).catch(function(error) {
                console.log("error", error);
            });
            return false;
        },
        update_cache_extensions: function() {
            if ('redis' === this.cache) {
                this.php_extensions.push('redis');
            } else {
                var redisIndex = this.php_extensions.indexOf('redis');
                if (redisIndex > -1) {
                    this.php_extensions.splice(redisIndex, 1);
                }
            }
        },
        update_database_extensions: function() {
            if (this.database_mongodb) {
                this.php_extensions.push('mongodb');
            } else {
                var mongoIndex = this.php_extensions.indexOf('mongodb');
                if (mongoIndex > -1) {
                    this.php_extensions.splice(mongoIndex, 1);
                }
            }
        }
    }
});