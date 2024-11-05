(function ($) {
    $(document).ready(function () {

        // console.log(ajax_url)
        // sorting
        let allData = {};
        function sorting(data) {
            let formData = new FormData();
            formData.append('action', 'sorting_func');

            // If data is an object, iterate with `for...in`
            if (data && typeof data === 'object') {
                for (let key in data) {
                    formData.append(key, data[key]);
                }
            }

            $.ajax({
                url: ajax_url,
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#salat_table_body').html(response);
                },
                error: function (err) {
                    console.log('error', err);
                }
            });
        }

        $('#filter-by-city').on('change', function () {
            let city = $(this).val();
            allData['city'] = city;
            // console.log('value', allData);
            sorting(allData);
        });


        $('#filter-by-month').on('change', function () {
            let month = $(this).val();
            allData['month'] = month;
            // console.log('value', allData);
            sorting(allData);
        });
        $('#filter-by-year').on('change', function () {
            let year = $(this).val();
            allData['year'] = year;
            sorting(allData);
        });

        $('#filter-by-day').on('change', function () {
            let day = $(this).val();
            allData['day'] = day;
            sorting(allData);
        });
        $('#reset_btn').on('click', function (e) {
            e.preventDefault();
            $('#filter-by-city').val('');
            $('#filter-by-month').val('');
            $('#filter-by-year').val('');
            $('#filter-by-day').val('');
            allData = {}
            sorting();
        })
        // sorting end

    });

})(jQuery);
