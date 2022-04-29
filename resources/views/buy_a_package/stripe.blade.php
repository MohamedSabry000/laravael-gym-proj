@extends('adminlte::page')

@section('title', 'Buy a Package for User')



@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper pb-4">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="col-sm-6">
                    <h4>Buy a Package for User</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Buy a Package for User</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="panel-body">
  
  @if (Session::has('success'))
      <div class="alert alert-success text-center">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
          <p>{{ Session::get('success') }}</p>
      </div>
  @endif

  <form 
          role="form" 
          action="{{ route('stripe.post') }}" 
          method="post" 
          class="require-validation"
          data-cc-on-file="false"
          data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
          id="payment-form">
      @csrf

      <div class="form-group">
            <label for="city">Users</label>
            <select required class=" form-control" name="user_id" id="users">
                <optgroup label="Available Users">
                    @foreach ($users as $user)
                    <option value={{ $user->id }}>{{ $user->name }} * {{ $user->email }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        <div class="form-group">
            <label for="city">Packages</label>
            <select required class=" form-control" name="package_id" id="package">
                <optgroup label="Available Package">
                    @foreach ($packages as $package)
                    <option value={{ $package->id }}>{{ $package->name }} => {{ $package->price }}$</option}}>
                    @endforeach
                </optgroup>
            </select>
        </div>

      <div class='form-row row'>
          <div class='col-xs-12 form-group required'>
              <label class='control-label'>Name on Card</label> <input
                  class='form-control' size='4' type='text'>
          </div>
      </div>

      <div class='form-row row'>
          <div class='col-xs-12 form-group card required'>
              <label class='control-label'>Card Number</label> <input
                  autocomplete='off' class='form-control card-number' size='20'
                  type='text'>
          </div>
      </div>

      <div class='form-row row'>
          <div class='col-xs-12 col-md-4 form-group cvc required'>
              <label class='control-label'>CVC</label> <input autocomplete='off'
                  class='form-control card-cvc' placeholder='ex. 311' size='4'
                  type='text'>
          </div>
          <div class='col-xs-12 col-md-4 form-group expiration required'>
              <label class='control-label'>Expiration Month</label> <input
                  class='form-control card-expiry-month' placeholder='MM' size='2'
                  type='text'>
          </div>
          <div class='col-xs-12 col-md-4 form-group expiration required'>
              <label class='control-label'>Expiration Year</label> <input
                  class='form-control card-expiry-year' placeholder='YYYY' size='4'
                  type='text'>
          </div>
      </div>

      <div class='form-row row'>
          <div class='col-md-12 error form-group hide'>
              <div class='alert-danger alert'>Please correct the errors and try
                  again.</div>
          </div>
      </div>

      <div class="row">
          <div class="col-xs-12">
              <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
          </div>
      </div>
        
  </form>
</div>
        

    </section>
    @section('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <style type="text/css">
            .panel-title {
            display: inline;
            font-weight: bold;
            }
            .display-table {
                display: table;
            }
            .display-tr {
                display: table-row;
            }
            .display-td {
                display: table-cell;
                vertical-align: middle;
                width: 61%;
            }
        </style>
    @stop
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    
        <script type="text/javascript">
        $(function() {
            
            var $form = $(".require-validation");
            
            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                                'input[type=text]', 'input[type=file]',
                                'textarea'].join(', '),
                $inputs       = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid         = true;
                $errorMessage.addClass('hide');
            
                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                    }
                });
            
                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }
            
            });
            
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];
                        
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }
            
        });
        </script>
    @section('js')

    @stop
</div>

@stop