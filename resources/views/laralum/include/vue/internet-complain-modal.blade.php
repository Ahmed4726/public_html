<script>
    new Vue({
        el : "#internet-complain",
        data : {
            comment : '',
            rejectUrl : "{{route('Laralum::internet-complain::list')}}",
            solveUrl : "{{route('Laralum::internet-complain::list')}}",
            deleteUrl : "{{route('Laralum::internet-complain::list')}}",
            assignUrl : "{{route('Laralum::internet-complain::list')}}",
            teams : @json($teams),
        },
        
        methods : {
            rejectModal(url) {
                this.comment = '';
                this.rejectUrl += url;
                $('.rejected.modal').modal('show');
            },

            solveModal(url) {
                this.comment = '';
                this.solveUrl += url;
                $('.solved.modal').modal('show');
            },
            
            deleteModal(url) {
                this.deleteUrl += url;
                $('.delete.modal').modal('show');
            },
            
            assignModal(url) {
                this.assignUrl += url;
                $('.assign.modal').modal('show');
            }
        }
    });
</script>