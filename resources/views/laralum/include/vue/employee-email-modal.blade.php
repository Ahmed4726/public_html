<script>
    new Vue({
        el : "#employee-email",
        data : {
            comment : '',
            rejectUrl : "{{route('Laralum::employee-email::list')}}",
            deleteUrl : "{{route('Laralum::employee-email::list')}}",
            completeUrl : "{{route('Laralum::employee-email::list')}}",
            currentAllUsernames : [],
            username : '',
        },
        
        methods : {
            rejectModal(url) {
                this.comment = '';
                this.rejectUrl += url;
                $('.rejected.modal').modal('show');
            },
            
            deleteModal(url) {
                this.deleteUrl += url;
                $('.delete.modal').modal('show');
            },
            
            completeModal(url, usernames) {
                this.currentAllUsernames = usernames.filter(Boolean);
                this.username = this.currentAllUsernames[0];
                this.completeUrl += url;
                $('.complete.modal').modal('show');
            }
        }
    });
</script>