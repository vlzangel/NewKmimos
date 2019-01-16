/**
 * Resize function without multiple trigger
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
      var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args); 
                timeout = null; 
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100); 
        };
    };

    // smartresize 
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };


/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');
   	
		/* DATA TABLES */
			
			function init_DataTables() {
				
				console.log('run_datatables');
				
				if( typeof ($.fn.DataTable) === 'undefined'){ return; }

				console.log('init_DataTables');
				
				var handleDataTableButtons = function() {
				  if ($(".datatable-buttons").length) {
					$(".datatable-buttons").DataTable({
					  columnDefs: [
				            {
				                "targets": [ 39, 40 ],
				                "visible": false
				            },
				        ],
					  dom: '<"col-md-6"B><"col-md-6"f><"#tblreserva"t>ip',
					  buttons: [
						// {
						//   extend: "copy",
						//   className: "btn-sm"
						// },
						{
						  extend: "csv",
						  className: "btn-sm",
						  exportOptions: {
			                    columns: [ 
				                	 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
				                	10,11,12,13,14,15,16,17,18,19,
				                	20,21,22,23,24,25,26,27,28,29,
				                	30,31,32,33,34,39,40,37,38
			                    ]
			                }
						},
						{
						  extend: "excelHtml5",
						  className: "btn-sm",
						  exportOptions: {
			                    columns: [ 
				                	 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
				                	10,11,12,13,14,15,16,17,18,19,
				                	20,21,22,23,24,25,26,27,28,29,
				                	30,31,32,33,34,39,40,37,38
			                    ]
			                }
						},
						// {
						//   extend: "pdfHtml5",
						//   className: "btn-sm"
						// },
						// {
						//   extend: "print",
						//   className: "btn-sm"
						// },
					  ],
					  responsive: false,
					  scrollX: true,
					  processing: true
					});
				  }
				};

				TableManageButtons = function() {
				  "use strict";
				  return {
					init: function() {
					  handleDataTableButtons();
					}
				  };
				}();

				$('#datatable').dataTable();

				$('#datatable-keytable').DataTable({
				  keys: true
				});

				$('#datatable-responsive').DataTable();

				$('#datatable-scroller').DataTable({
				  ajax: "js/datatables/json/scroller-demo.json",
				  deferRender: true,
				  scrollY: 380,
				  scrollCollapse: true,
				  scroller: true
				});

				$('#datatable-fixed-header').DataTable({
				  fixedHeader: true
				});

				var $datatable = $('#datatable-checkbox');

				$datatable.dataTable({
				  'order': [[ 1, 'asc' ]],
				  'columnDefs': [
					{ orderable: true, targets: [0] }
				  ]
				});
				$datatable.on('draw.dt', function() {
				  $('checkbox input').iCheck({
					checkboxClass: 'icheckbox_flat-green'
				  });
				});

				TableManageButtons.init();
				$('#adminmenuwrap').css('position', 'fixed');
			};
	   
	$(document).ready(function() {
		init_DataTables();	

	});	
	


})(jQuery,'smartresize');
