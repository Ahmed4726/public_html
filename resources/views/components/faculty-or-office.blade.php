<div id="faculty_office_component" v-cloak>
    <div class="form-group row required">
        <label for="facultyOrOffice" class="col-form-label">Faculty / Office</label>
        <select id="facultyOrOffice" class="form-control jufbs @error('faculty_office') is-invalid @enderror"
            name="faculty_office" @change="getFacultyOrOfficeList()" v-model="faculty_office" required>
            <option value="">Please Select a Type</option>
            @foreach (Helper::facutlyOrOffice() as $key => $value)
            <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
        @error('faculty_office')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group row" v-if="faculty_office == 'faculty'">
        <label for="faculty" class="col-form-label">Faculty</label>
        <select id="faculty" class="form-control jufbs" @change="getDepartmentList()" v-model="faculty_id"
            name="faculty_id">
            <option value="">Please Select a Faculty</option>
            @foreach ($faculties as $faculty)
            <option value="{{$faculty->id}}">{{$faculty->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group row required" v-if="faculty_office == 'faculty'">
        <label for="department" class="col-form-label">Department</label>
        <select id="department" name="department_id" v-model="department_id"
            class="form-control jufbs @error('department_id') is-invalid @enderror" required>
            <option value="">Please Select a department</option>
            <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                @{{department.name}}
            </option>
        </select>
        @error('department_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group row required" v-if="faculty_office == 'institute'">
        <label for="department" class="col-form-label">Institute</label>
        <select id="department" name="department_id" v-model="department_id"
            class="form-control jufbs @error('department_id') is-invalid @enderror" required>
            <option value="">Please Select a institute</option>
            <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                @{{department.name}}
            </option>
        </select>
        @error('department_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group row required" v-if="faculty_office == 'office'">
        <label for="office" class="col-form-label">Office</label>
        <select id="office" name="office_id" v-model="office_id"
            class="form-control jufbs @error('office_id') is-invalid @enderror" required>
            <option value="">Please Select a office</option>
            <option v-for="office in offices" v-cloak :value="office.id">
                @{{office.name}}
            </option>
        </select>
        @error('office_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group row required" v-if="faculty_office == 'other'">
        <label for="other" class="col-form-label">Other</label>
        <input type="text" id="other" name="other" placeholder="Other ..."
            class="form-control @error('other') is-invalid @enderror" value="{{old('other')}}" required>
        @error('other')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

@push('footerScript')
@include('laralum.include.vue.vue-axios')

<script>
    new Vue({
        el : "#faculty_office_component",
        data : {
            departments : [],
            department_id : "{{old('department_id')}}",
            offices : [],
            office_id : "{{old('office_id')}}",
            faculty_id : "{{old('faculty_id')}}",
            faculty_office: "{{old('faculty_office')}}",
            institute_id : "{{Helper::getInstitute()->id ?? false}}"
        },
        
        methods : {
            getDepartmentList() {
                this.$http.get("{{ route('Frontend::department::search') }}", {
                    params: {
                        faculty_id : (this.faculty_office == 'institute') 
                        ? this.institute_id
                        : (this.faculty_id || @json($faculties).map(faculty => faculty.id)),
                    }
                }).
                then(response => {
                    this.departments = response.data
                });
            },

            getOfficeList() {
                this.$http.get("{{ route('Frontend::office::search') }}").
                then(({data}) => {
                    this.offices = data.data;
                });
            },

            getFacultyOrOfficeList () {
                if(['faculty', 'institute'].includes(this.faculty_office)){
                    this.getDepartmentList();
                }

                if(this.faculty_office == 'office'){
                    this.getOfficeList();
                }
            }
        },
        
        created () {
            this.getFacultyOrOfficeList();
        }
    });
</script>

@endpush