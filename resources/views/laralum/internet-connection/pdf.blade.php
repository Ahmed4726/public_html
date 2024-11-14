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
        <h3>Application for Internet Connection</h3>
    </div>

    {{-- <div style="margin-top:10%">All fields are mandatory</div> --}}
    <div style="margin-top:10%" class="box1">

        <div class="flex">
            <div class="field">Name</div>
            <div class="value">: {{$connection->name}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Designation</div>
            <div class="value">: {{$connection->designation}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Department / Office</div>
            <div class="value">: {{ $connection->facultyOrOffice() }}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Address</div>
            <div class="value">: {{$connection->address}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Email</div>
            <div class="value">: {{$connection->email}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Phone Number</div>
            <div class="value">: {{$connection->phone_no}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Comment</div>
            <div class="value">: {{$connection->comment}}</div>
        </div>

    </div>

    <div style="text-align: center; margin-top:10%"><strong>For Office use only</strong></div>

    <div class="box1">
        <div class="flex">
            <div class="field">Account Created On</div>
            <div class="value">: {{optional($connection->completed_at)->format('Y-m-d h:i:s a')}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Account Created By</div>
            <div class="value">: {{optional($connection->completedBy)->name}}</div>
        </div>
    </div>

</body>

</html>