<script>
    "use strict";

    $('select#ad_unit').select2({
        theme: 'bootstrap4',
        selectOnClose: true,
        minimumResultsForSearch: -1,
        ajax: {
            url: "{{ route('advertisement.search') }}",
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name + ' (' + item.size + ')'
                        }
                    })
                }
            }

        }
    })
</script>
