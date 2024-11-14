<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application for Opening E-mail Account</title>
    <style>
        body {
            margin: auto;
            width: 50%;
            border: 1px solid #D1D5DB;
            padding: 10px;
        }

        .flex {
            /* display: flex; */
            margin: 2%
        }

        .field {
            float: left;
            width: 30%;
            font-weight: 600
        }

        .box1 {
            border: 1px solid #E5E7EB;
            margin-top: 1%;
        }

        hr {
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
        }

        .signature {
            width: 30%;
            margin-top: 10%;
        }
    </style>
</head>

<body>

    <div style="text-align: center">
        <div>Jahangirnagar University</div>
        <div>Savar, Dhaka</div>
        <h3>Application for Opening E-mail Account</h3>
        <div>(Teachers and Officers only)</div>
        <div>Please send the filled form to: <strong>email@juniv.edu</strong></div>
    </div>

    {{-- <div style="margin-top:10%">All fields are mandatory</div> --}}
    <div style="margin-top:10%" class="box1">

        <div class="flex">
            <div class="field">Name</div>
            <div class="value">: {{$email->name}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Employee ID</div>
            <div class="value">: {{$email->employee_id}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Designation</div>
            <div class="value">: {{$email->employeeType->name}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Department / Office</div>
            <div class="value">: {{ $email->facultyOrOffice()}}</div>
        </div>
        <hr>

        @if ($email->organization == 'other')
        <div class="flex">
            <div class="field">Other</div>
            <div class="value">: {{ $email->other}}</div>
        </div>
        <hr>
        @endif

        <div class="flex">
            <div class="field">Phone Number</div>
            <div class="value">: {{$email->phone_no}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Current Email</div>
            <div class="value">: {{$email->current_email}}</div>
        </div>
        <hr>

        @if ($email->expected_email_1)
        <div class="flex">
            <div class="field">Expected Email 1</div>
            <div class="value">: {{$email->expected_email_1. App\EmployeeEmail::$emailDomain}}</div>
        </div>
        <hr>
        @endif

        @if ($email->expected_email_2)
        <div class="flex">
            <div class="field">Expected Email 2</div>
            <div class="value">: {{$email->expected_email_2. App\EmployeeEmail::$emailDomain}}</div>
        </div>
        <hr>
        @endif

        @if ($email->expected_email_3)
        <div class="flex">
            <div class="field">Expected Email 3</div>
            <div class="value">: {{$email->expected_email_3. App\EmployeeEmail::$emailDomain}}</div>
        </div>
        @endif

    </div>

    <div class="signature">
        <hr>
        <div>Signature of applicant</div>
        <div>Date :</div>
    </div>

    <div style="text-align: center; margin-top:10%"><strong>For Office use only</strong></div>
    <div class="box1">
        <div class="flex">
            <div class="field">Account Created On</div>
            <div class="value">: {{optional($email->completed_at)->format('Y-m-d H:i:s')}}</div>
        </div>
        <hr>
        <div class="flex">
            <div class="field">Account Name</div>
            <div class="value">: {{$email->username}}</div>
        </div>
        <hr>
        <div class="flex">
            <div class="field">Initial Password</div>
            <div class="value">: {{$email->password}}</div>
        </div>
        <hr>
        <div class="flex">
            <div class="field">Account Created By</div>
            <div class="value">: {{optional($email->completedBy)->name}}</div>
        </div>
    </div>

</body>

</html>