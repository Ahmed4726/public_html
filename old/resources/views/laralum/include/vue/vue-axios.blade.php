<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-scrollto"></script>

<script type="text/javascript">
    Vue.filter('str_limit', function (value, size) {
        if (!value) return '';
        value = value.toString();

        if (value.length <= size) {
            return value;
        }
        return value.substr(0, size) + '...';
    });

    Vue.use('vue-scrollto',VueScrollTo);

    Vue.prototype.$http = axios.create({
        baseURL: "{{url('')}}",
    });

    Vue.component('vue-read-more', {
        template : `<div>
                        <span v-if="!readMore" :inner-html.prop="text | str_limit(limit)"></span>
                        <a class="" v-if="!readMore" @click.prevent="readMore=true" href="#">
                        Read more
                        </a>
                        <span v-if="readMore" v-html="text"></span>
                    </div>`,
        data(){
            return {
                readMore : false
            } 
        },

        props: {
            text: String,
            limit: {
                type : Number,
                default : 200
            }
        }
    })

    Vue.component('vue-pagination', {
        data: function () {
            return {
                lastNumber: 0,
                startNumber: 0
            }
        },
        props: {
            pagination: {
                type: Object,
                required: true
            },
            offset: {
                type: Number,
                default: 3
            }
        },

        template : '<ul class="pagination" v-if="pagination.last_page != 1">\n' +
            '    <li v-if="pagination.last_page > 1" :class="[{\'disabled\': 1 == pagination.current_page}, \'page-item\']">\n' +
            '        <a  class="page-link" href="javascript:void(0)" aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)">\n' +
            '            <span aria-hidden="true">«</span>\n' +
            '            </a>\n' +
            '        </li>\n' +
            '    <li v-if="pagination.current_page>(this.offset+1)" class="page-item">\n' +
            '        <a  class="page-link" href="javascript:void(0)" @click.prevent="changePage(1)">1</a>\n' +
            '        </li>\n' +
            '    <li v-if="pagination.current_page>(this.offset+1)" class="page-item disabled">\n' +
            '        <span class="page-link">...</span>\n' +
            '        </li>\n' +
            '    <li v-for="page in pagesNumber" :class="[{\'active\': page == pagination.current_page}, \'page-item\']">\n' +
            '        <a  class="page-link" href="javascript:void(0)" @click.prevent="changePage(page)">@{{ page }}</a>\n' +
            '        </li>\n' +
            '    <li v-if="pagination.last_page && this.lastNumber!=pagination.last_page" class="page-item disabled">\n' +
            '        <span class="page-link">...</span>\n' +
            '        </li>\n' +
            '    <li v-if="pagination.last_page && this.lastNumber!=pagination.last_page" class="page-item">\n' +
            '        <a  class="page-link" href="javascript:void(0)" @click.prevent="changePage(pagination.last_page)">@{{ pagination.last_page }}</a>\n' +
            '        </li>\n' +
            '    <li v-if="pagination.last_page > 1" :class="[{\'disabled\': pagination.last_page == pagination.current_page}, \'page-item\']">\n' +
            '        <a  class="page-link" href="javascript:void(0)" aria-label="Next" @click.prevent="changePage(pagination.current_page + 1)">\n' +
            '            <span aria-hidden="true">»</span>\n' +
            '            </a>\n' +
            '        </li>\n' +
            '    </ul>',

        computed: {
            pagesNumber() {
                if (!this.pagination.to) {
                    return [];
                }
                this.startNumber = this.pagination.current_page - this.offset;
                if (this.startNumber < 1) {
                    this.startNumber = 1;
                }
                let to = this.startNumber + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                let pagesArray = [];
                for (let page = this.startNumber; page <= to; page++) {
                    this.lastNumber = page;
                    pagesArray.push(page);
                }
                return pagesArray;
            }
        },
        methods : {
            changePage(page) {
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        }
    });

</script>