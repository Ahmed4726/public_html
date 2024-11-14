<div class="field" id="faculty_office_component" v-cloak>
    <div class="field required">
        <label>Department / Office</label>
        <select name="faculty_office" v-model="faculty_office" required @change="getFacultyOrOfficeList()">
            @foreach (Helper::facutlyOrOffice() as $key => $value)
            <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>

    <div class="field" v-if="faculty_office == 'faculty'">
        <label>Faculty</label>
        <select name="faculty_id" v-model="faculty_id" @change="getDepartmentList()">
            @foreach($faculties as $key => $faculty)
            <option value="{{ $faculty->id }}">
                {{ $faculty->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="field required" v-if="faculty_office == 'faculty'">
        <label>Department</label>
        <select name="department_id" required v-model="department_id">
            <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                @{{department.name}}
            </option>
        </select>
    </div>

    <div class="field required" v-if="faculty_office == 'institute'">
        <label>Institute</label>
        <select name="department_id" required v-model="department_id">
            <option v-for="(department, index) in departments.data" v-cloak :value="department.id">
                @{{department.name}}
            </option>
        </select>
    </div>

    <div class="field required" v-if="faculty_office == 'office'">
        <label>Office</label>
        <select name="office_id" required v-model="office_id">
            <option v-for="office in offices" v-cloak :value="office.id">
                @{{office.name}}
            </option>
        </select>
    </div>

    <div class="field required" v-if="faculty_office == 'other'">
        <label>Other</label>
        <input type="text" name="other" placeholder="Other..." value="{{old('other', $model->other)}}" required>
    </div>
</div>

@push('js')
@include('laralum.include.vue.vue-axios')

<script type="text/javascript">
    new Vue({
        el : "#faculty_office_component",
        data : {
            departments : [],
            department_id : "{{old('department_id', $model->department_id)}}",
            offices : [],
            office_id : "{{old('office_id', $model->office_id)}}",
            faculty_id : "{{old('faculty_id', optional($model->department)->faculty_id)}}",
            faculty_office: "{{old('faculty_office', $model->facultyOrOfficeKey())}}",
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