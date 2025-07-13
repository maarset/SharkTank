 $(document).ready(function () {
	 
 	$(".ts-sidebar-menu li a").each(function () {
 		if ($(this).next().length > 0) {
 			$(this).addClass("parent");
 		};
 	})
 	var menux = $('.ts-sidebar-menu li a.parent');
 	$('<div class="more"><i class="fa fa-angle-down"></i></div>').insertBefore(menux);
 	$('.more').click(function () {
 		$(this).parent('li').toggleClass('open');
 	});
	$('.parent').click(function (e) {
		e.preventDefault();
 		$(this).parent('li').toggleClass('open');
 	});
 	$('.menu-btn').click(function () {
 		$('nav.ts-sidebar').toggleClass('menu-open');
 	});
	 
	  

//--------------------------------------zctb1------------------------------------------------//

var minDate1, maxDate1;
 
					// Custom filtering function which will search data in column four between two values
					DataTable.ext.search.push(function (settings, data, dataIndex) {
					    var min1 = minDate1.val();
					    var max1 = maxDate1.val();
					    var date1 = new Date(data[3]);
					
					    if (
					        (min1 === null && max1 === null) ||
					        (min1 === null && date1 <= max1) ||
					        (min1 <= date1 && max1 === null) ||
					        (min1 <= date1 && date1 <= max1)
					    ) {
					        return true;
					    }
					    return false;
					});

					// Create date inputs
					minDate1 = new DateTime('#min1', {
					    format: 'MMMM Do YYYY'
					});
					maxDate1 = new DateTime('#max1', {
					    format: 'MMMM Do YYYY'
					});

					// DataTables initialisation
					 new DataTable('#zctb1', {
					    info: false,
					    ordering: true,
					    paging: true,
						dom: 'lBfrtip',
						 order: [[4, 'asc']],
						lengthMenu: [10, 25, 50, -1],
						buttons: [{
						     extend: 'pdf',
						     title: 'Customized PDF Title',
						     filename: 'customized_pdf_file_name'
						   }, {
						     extend: 'excel',
						     title: 'Customized EXCEL Title',
						     filename: 'customized_excel_file_name'
						   }, {
						     extend: 'csv',
						     filename: 'customized_csv_file_name'
						   }],
//
					"oLanguage": {
					      "sSearch": "Filter Data"
					    },
					    //"iDisplayLength": -1,
						//"iDisplayLength": 10, //HAL HAL HAL HAL
					    "sPaginationType": "full_numbers",
					
						footerCallback: function (row, data, start, end, display) {
					        let api = this.api();
						
					        // Remove the formatting to get integer data for summation
					        let intVal = function (i) {
					            return typeof i === 'string'
					                ? i.replace(/[\$,]/g, '') * 1
					                : typeof i === 'number'
					                ? i
					                : 0;
					        };
						
					        // Total over all pages
					        total = api
					            .column(2)
					            .data()
					            .reduce((a, b) => intVal(a) + intVal(b), 0);
						
					        // Total over this page
					        pageTotal = api
					            .column(2, { page: 'current' })
					            .data()
					            .reduce((a, b) => intVal(a) + intVal(b), 0);
						
					        // Update footer
					        api.column(2).footer().innerHTML =
					          //  '$' + pageTotal + ' ( $' + total + ' total)';
							    '$' + pageTotal ;
					    }
					
					});
					var table = $('#zctb1').DataTable();

					// Refilter the table
					$('#min1, #max1').on('change', function () {
					//	alert("DRAWING TABLE");
					  //  table.draw();
					   $('#zctb1').DataTable().draw();
					});

//--------------------------------------zctb2------------------------------------------------//
 new DataTable('#zctb2', {
    info: false,
    ordering: true,
    paging: true,
	 order: [[3, 'asc']],
	lengthMenu: [10, 25, 50, -1]
	
    // ,lengthMenu: [
    //    [10, 25, 50, "All"]
    //]
});


//--------------------------------------zctb3------------------------------------------------//

var minDate3, maxDate3;
 
					// Custom filtering function which will search data in column four between two values
					DataTable.ext.search.push(function (settings, data, dataIndex) {
					    var min3 = minDate3.val();
					    var max3 = maxDate3.val();
					    var date3 = new Date(data[2]);
					
					    if (
					        (min3 === null && max3 === null) ||
					        (min3 === null && date3 <= max3) ||
					        (min3 <= date3 && max3 === null) ||
					        (min3 <= date3 && date3 <= max3)
					    ) {
					        return true;
					    }
					    return false;
					});

					// Create date inputs
					minDate3 = new DateTime('#min3', {
					    format: 'MMMM Do YYYY'
					});
					maxDate3 = new DateTime('#max3', {
					    format: 'MMMM Do YYYY'
					});

					// DataTables initialisation
					 new DataTable('#zctb3', {
					    info: false,
					    ordering: true,
					    paging: true,
						 order: [[2, 'asc']],
						lengthMenu: [10, 25, 50, -1],
//
					"oLanguage": {
					      "sSearch": "Filter Data"
					    },
					  //  "iDisplayLength": -1,
					    "sPaginationType": "full_numbers",
					
						footerCallback: function (row, data, start, end, display) {
					        let api = this.api();
						
					        // Remove the formatting to get integer data for summation
					        let intVal = function (i) {
					            return typeof i === 'string'
					                ? i.replace(/[\$,]/g, '') * 1
					                : typeof i === 'number'
					                ? i
					                : 0;
					        };
						
					        // Total over all pages
					        total = api
					            .column(1)
					            .data()
					            .reduce((a, b) => intVal(a) + intVal(b), 0);
						
					        // Total over this page
					        pageTotal = api
					            .column(1, { page: 'current' })
					            .data()
					            .reduce((a, b) => intVal(a) + intVal(b), 0);
						
					        // Update footer
					        api.column(1).footer().innerHTML =
					          //  '$' + pageTotal + ' ( $' + total + ' total)';
							    '$' + pageTotal ;
					    }
					
					});
					var table = $('#zctb3').DataTable();

					// Refilter the table
					$('#min3, #max3').on('change', function () {
					//	alert("DRAWING TABLE");
					  //  table.draw();
					   $('#zctb3').DataTable().draw();
					});





  
});
 
