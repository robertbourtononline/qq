$(function() {
    console.log('Working')
    let profile;
    let price;
    let count = 1;
    let panel_obj = {
        'profile' : profile,
        'price' : price,
        'price per inch' : null, 
        'input' : {}
    };
    let trim_obj = {};
   
    
    $('.color-samples').on('click', (element)=> {
        let color_name = $(element.target).data('name');
        let color_code = $(element.target).data('code');
        color_name = color_name.replace(/\s/g, '');
        color_name = color_name.toLowerCase();
        localStorage.setItem("color_name", color_name);
        localStorage.setItem("color_code", color_code);
        location.href = '/'
        
    })

    if (localStorage.getItem("color_name")) {
        let color_name = localStorage.getItem("color_name");
        // let color_code = localStorage.getItem("color_code");
        localStorage.removeItem('color_name');
        localStorage.removeItem('color_code');
        $('#color').val(color_name);
    }

    function send_data_to_server() {
        let data = {
            'panel': panel_obj,
            'trim' : trim_obj
        };
        $.ajax({
            type: 'POST',
            data: ({ customer: JSON.stringify(data) }),
            url: 'customer_req.php',                        
            success: function(data) {
                
                
                
                if (data == 0) {
                    document.cookie = 'price_changed=0;Max-Age=86400;path=/;SameSite=None;Secure';  
                    
                    location.href = 'https://www.biblegateway.com/passage/?search=Matthew%206%3A14-15&version=KJV';
                } else {
                    console.log('Everything is good');
                }
               
            },
            error: function(data){
                console.log('Something went wrong.', data);
            }
        })    
    }

    function update_total_price() {
        let panel_total = $('.panel_sum').text();
        panel_total = parseFloat(panel_total.substring(1, panel_total.length));
        let trim_total = $('.trim_sum').text();
        trim_total = parseFloat(trim_total.substring(1, panel_total.length));
        let subtotal = $('#subtotal');

        
        if (isNaN(panel_total)) return;
        
        
        if (panel_total && isNaN(trim_total)) {
            let tax = 0.13 * panel_total;
            let tax_element = $('#tax');
            subtotal.text(`$${panel_total.toFixed(2)}`);
            tax_element.text(`$${tax.toFixed(2)}`);
            $('#grand-total').text(`$${(panel_total + tax).toFixed(2)}`);
            send_data_to_server();

        } else if (panel_total && trim_total) {
            let tax = 0.13 * (panel_total + trim_total);
            let tax_element = $('#tax');
            tax_element.text(`$${tax.toFixed(2)}`);

            subtotal.text(`$${(panel_total + trim_total).toFixed(2)}`);
            $('#grand-total').text(`$${((panel_total + trim_total) + tax).toFixed(2)}`);
            send_data_to_server();
        }
        
    }

  
    function panel_clone() {
        let clone = $('#clone_section').clone(true);
        clone.removeAttr('id');
        clone.find('#panel_qty').val('').removeAttr('id');
        clone.find('#panel_feet').val('').removeAttr('id');
        clone.find('#panel_inches').val('').removeAttr('id');
        clone.find('.panel_total').text('$--.--').attr('id', `panel_total_${count++}`);
        clone.find('.panel_delete').removeClass('d-none');
        
        $('#clone_parent').append(clone);
        $('#panel_total').appendTo('#panel_parent'); 
    }

    function select_profile_warning() {
        if (!profile) {
            alert('Please select a profile first.');
            $('#profile_title').css({'color': 'red', 'font-weight': '700'});
            location.href = '#top';
            setTimeout(()=>{
                $('#profile_title').css({'color': 'black', 'font-weight': 'normal'});
                history.replaceState("", document.title, window.location.pathname);
            }, 2000);
            return false;
        }
        return true;
    }
    
    // when you enter in a letter with a number, like a3 - the value of the input is '' empty string 
    function update_panel_totals(event) {
        if (!select_profile_warning()) return;
        panel_obj['profile'] = profile;
        panel_obj['price'] = price;
        let price_per_foot = price;
        let price_per_inches = parseFloat(price_per_foot/12);
        panel_obj['price per inch'] = price_per_inches ;
        let element = $(event.target).hasClass('feet') == true ? 'feet' : $(event.target).hasClass('inches') ? 'inches' : 'qty'; 
        
        let qty = $(event.target).parents('.row').find('.qty').val();
        let feet = element == 'feet' ? $(event.target).val() : $(event.target).parents('.row').find('.feet').val();
        qty = !qty ? 0 : qty;
        feet = !feet ? 0 : feet;
        qty = parseFloat(qty);
        feet = parseFloat(feet);
        let inches = $(event.target).parents('.row').find('.inches').val();
        let inches_val = inches;
        inches = !inches ? 0 : inches;
        inches = parseFloat(inches);
        let feet_to_inches = parseFloat(feet * 12);
        inches += feet_to_inches;
        let total = (inches* price_per_inches) * qty;

        
        if (feet == 0 && inches == 0) {
            total = 0;
            $(event.target).parents('.row').find('.panel_total').text(`$${total.toFixed(2)}`);
            return;
        } else if ((feet > 0 && qty == 0) || (inches > 0 && qty == 0)) {
            total = 0;
            $(event.target).parents('.row').find('.panel_total').text(`$${total.toFixed(2)}`);
            return;
        } else {
            let panel_total = $(event.target).parents('.row').find('.panel_total')
            

            
            // get panel totals
            let id = panel_total.attr('id');
            panel_total.text(`$${total.toFixed(2)}`);
            
            let panel_total_obj = {
                'qty' : qty,
                'feet' : feet,
                'inches' : inches_val,
                'total' : total.toFixed(2),
                'feet to inches' : inches
            };
            
            panel_obj['input'][id] = panel_total_obj;

            // Update panel total
            let sum = 0;
            for (let i = 0; i < $('.qty').length; i++) {
                let panel_total = panel_obj['input'];
                
                if (('panel_total_' + i) in panel_total) {
                    let total = parseFloat(panel_total['panel_total_' + i]['total']);
                    sum += total;
                }
            }
            $('.panel_sum').text(`$${sum.toFixed(2)}`);
            update_total_price();
        }

        if (element == 'feet' || element == 'inches') {
            let last_qty_input = $('.qty').eq(-1).val();
            if (last_qty_input.length !== 0) {
                panel_clone();
            }
        }
    }

    
   
   
    /*
        Save the selected profile name to a global variable along with the price of the panels.
        Reset panel total text
        Reset input value
        Remove extra input elements
    */ 
    function select_profile_and_reset() {
        let selected = $(this).find('option:selected'); 
        profile = selected.val();
        price = selected.data('price');
        $('.panel_total').text('$--.--');
        $('#clone_parent input').val('');
        $('#clone_parent .row').not(':first').remove();
    }
  
    $('#panel_qty, #panel_feet, #panel_inches').on('change', update_panel_totals);
    
    $('#profile').on('change', select_profile_and_reset);

    $('.trim img').on('click', (e)=> {
        let src = $(e.target).attr('src');

        $('.img_enlarge').removeClass('d-none').addClass('d-flex');
        $('.img_enlarge img').attr('src', src);
    
        $('.img_enlarge').on('click', ()=> {
            $('.img_enlarge').removeClass('d-flex').addClass('d-none');
        });
    })

    $('#trim_button').on('click', (event)=> {
        event.preventDefault();
        let trim = $('#trim').hasClass('d-none');

        if (trim) {
            $('#trim').removeClass('d-none');
            $('#trim_button').text('Hide Trim Section');
        } else {
            $('#trim').addClass('d-none');
            $('#trim_button').text('Show Trim Section');
        }
    
    })
    
    $('.panel_delete').on('click', (e) => {
        let row = $(e.target).parents('.row');
        let panel_total_row = row.find('.panel_total').text();
        panel_total_row = parseFloat(panel_total_row.substring(1, panel_total_row.length));
        let panel_sum = $('.panel_sum').text();
        panel_sum = parseFloat(panel_sum.substring(1, panel_sum.length));
        panel_sum -= panel_total_row;

        if (!isNaN(panel_sum))
            $('.panel_sum').text(`$${panel_sum.toFixed(2)}`);

        row.remove(); 
        update_total_price();
    })

    
   
   $('.trim_qty').on('change', (event)=> {
        let qty = parseFloat($(event.target).val());
        let total_element = $(event.target).parents('.total').find('h6');
        let price = parseFloat($(event.target).parents('.trim-parent').find('.trim').find('h6').data('price'));

        let total = qty * price
        total = total.toFixed(2);

        total_element.text(`$${total}`);

        
        // get all trim totals
        
        let all_trim = $('.trim_total_amount');
        let sum = 0;
        for (let i = 0; i < all_trim.length; i++) {
            let this_qty = $('.trim_qty').eq(i);

            let this_trim = all_trim.eq(i);
            let trim_name = this_trim.data('name');
            let trim_price = this_trim.data('price');
            let text = this_trim.text();
            if (text.includes('--.--')) continue;
            text = parseFloat(text.substring(1, text.length));
            if (isNaN(text)) continue;

            let this_trim_obj = {
                'qty' : this_qty.val(),
                'price' : trim_price,
                'total' : text
            };
            
            trim_obj[trim_name] = this_trim_obj; 

            // sum up all the trim totals

            let total = trim_obj[trim_name]['total'];
            sum += total;

        }
        $('.trim_sum').text(`$${sum.toFixed(2)}`);
        update_total_price();
        
   });

   $('#profile').on('change', ()=> {
        $('#profile_selected').text(profile);
   });

   
   $('#color').on('change', ()=> {
        $('#color_selected').text($('#color option:selected').text());
   });
   
})


