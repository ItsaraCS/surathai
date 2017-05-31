        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(e) {
            //--Variable
            var factory = new Factory();
            var ajaxUrl = 'http://210.4.143.51/Surathai01/API/userAPI.php';
            var params = {};

            //--Page load
            setInit();

            //--Function
            function setInit() {
                factory.initService.setMenu();
                factory.utilityService.getDataImportant();
                
                $.datepicker.regional['th'] = { //--Datepicker
                    dateFormat: 'dd/mm/yy',
                    changeMonth: true,
                    changeYear: true,
                    yearOffSet: 543
                };
                $.datepicker.setDefaults($.datepicker.regional['th']);
                $('.datepicker').datepicker($.datepicker.regional['th']);
                $('.datepicker').datepicker('setDate', new Date());

                params = {
                    fn: 'profile'
                };
                
                factory.connectDBService.sendJSONObj(ajaxUrl, params).done(function(res) {
                    if(res != undefined){
                        var data = JSON.parse(res);
                        //console.log(data);

                        localStorage.setItem('userName', data.fullname);
                        localStorage.setItem('userPosition', data.ProvinceTXT);
                        
                        $('.nav-menu #Province').attr('data-provice', data.Province);
                        $('#ProvinceTXT').html(data.ProvinceTXT);
                        $('.user-menu span, .user-menu-detail-label p').html(localStorage.getItem('userName'));
                        $('.user-menu-detail-label span').html(localStorage.getItem('userPosition'));
                    }
                });
            }

            //--Event
            $(document).on('click', '.datepicker-btn', function(e){
                e.preventDefault();

                $(this).closest('.input-group').find('input').focus();
            });

            $(document).on('click', '.user-menu', function(e) {
                e.stopPropagation();

                $('.user-menu-detail').toggleClass('show');
            });

            $(window).click(function(e) {
                if($('.user-menu-detail').hasClass('show')) 
                    $('.user-menu-detail').toggleClass('show');
            });
        });
    </script>
</body>
</html>