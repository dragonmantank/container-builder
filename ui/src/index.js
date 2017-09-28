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

Vue.component('phpplugins', {
    components: { InputTag },
    template: '#phpplugins-template',
    methods: {
        tags_changed: function() {
            this.$emit('tags_changed', arguments);
        }
    },
    data () {
        return {
            tags: ['mbstring', 'zip', 'xdebug', 'gd', 'intl', 'xml', 'curl', 'json', 'pdo', 'pdo_mysql'],
            placeholder: 'Add Extension'
        }
    }
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
    data: {
        cli: 'checked',
        composer: 'checked',
        database: 'mysql',
        database_envvars: [
            {id: 0, name: 'MYSQL_ROOT_PASSWORD', value: 'rootpassword'},
            {id: 1, name: 'MYSQL_USER', value: 'dbuser'},
            {id: 2, name: 'MYSQL_PASSWORD', value: 'dbuser'},
            {id: 3, name: 'MYSQL_DATABASE', value: 'appdb'}
        ],
        database_envvar_id: 4,
        database_version: '5.7',
        mailhog: 'checked',
        php_extensions: ['mbstring', 'zip', 'xdebug', 'gd', 'intl', 'xml', 'curl', 'json', 'pdo', 'pdo_mysql'],
        php_version: '7.1',
        queue: '',
        webserver: 'apache',
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
        remove_database_envvar: function(id) {
            for(var index in this.database_envvars) {
                if (id === this.database_envvars[index].id) {
                    this.database_envvars.splice(index, 1);
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
                    composer: this.composer,
                    cli: this.cli,
                    database: this.database,
                    database_envvars: this.database_envvars,
                    database_version: this.database_version,
                    mailhog: this.mailhog,
                    php_extensions: this.php_extensions,
                    php_version: this.php_version,
                    queue: this.queue,
                    webserver: this.webserver,
                    webserver_mountpoints: this.webserver_mountpoints,
                    webserver_ports: this.webserver_ports
                }
            }).then(function(response) {
                saveData(response.data, 'container-builder.zip');
            }).catch(function(error) {
                console.log("error", error);
            });
            return false;
        }
    }
});