<script setup>
import {ref, onMounted, computed} from 'vue';
import axios from '../axios';
import {createToaster} from "@meforma/vue-toaster";

const toaster = createToaster({position: 'bottom-right'});

const companies = ref([]);
const selectedFile = ref(null);
const loadingData = ref(false);
const exportProcessing = ref(false);
const importProcessing = ref(false);
const pagination = ref({
    current_page: 1,
    last_page: 1,
});
const filter = ref('');
const totalCount = ref(0)

onMounted(() => {
    fetchCompanies();
});

function handleFileChange(event) {
    selectedFile.value = event.target.files[0];
}

async function fetchCompanies(page = 1) {
    loadingData.value = true;
    totalCount.value = 0;

    try {
        const response = await axios.get('/companies', {
            params: {
                page: page,
                filter: filter.value,
            },
        });

        companies.value = response.data.data;
        pagination.value = response.data.meta;
        totalCount.value = response.data.meta.total;
    } catch (err) {
        toaster.error('Failed to fetch companies');
    } finally {
        loadingData.value = false;
    }
}

// Show max 5 pages in pagination
const pagesToShow = computed(() => {
    const pages = [];
    const start = Math.max(1, pagination.value.current_page - 2);
    const end = Math.min(pagination.value.last_page, start + 4);

    for (let i = start; i <= end; i++) pages.push(i);

    return pages;
});

async function importCompanies() {
    if (!selectedFile.value) {
        toaster.error('Please select a CSV file first.');
        return;
    }

    importProcessing.value = true;

    const formData = new FormData();
    formData.append('file', selectedFile.value);

    try {
        const response = await axios.post('/companies/import', formData, {
            headers: {'Content-Type': 'multipart/form-data'},
        })

        toaster.success(response.data.message);

        selectedFile.value = null;
        document.querySelector('input[type="file"]').value = '';
    } catch (error) {
        toaster.error(error.response?.data?.message || 'Import failed.');
    } finally {
        importProcessing.value = false;
    }
}

function exportCompanies() {
    exportProcessing.value = true;

    axios
        .get('/companies/export', {responseType: 'blob'})
        .then((res) => {
            const url = window.URL.createObjectURL(new Blob([res.data]));
            const link = document.createElement('a');
            link.href = url;
            const timeStamp = Date.now();
            link.setAttribute('download', `companies_export_${timeStamp}.csv`);
            document.body.appendChild(link);
            link.click();
            link.remove();

            exportProcessing.value = false;
            toaster.success('Data export successful.');
        })
        .catch(() => {
            exportProcessing.value = false;
            toaster.error('Data export failed.');
        });
}
</script>

<template>
    <div>
        <div class="d-flex justify-content-between mb-2">
            <div class="d-flex align-items-center flex-wrap">
                <h3>Company List</h3>

                <div class="btn-group ms-3" role="group" aria-label="Filter companies">
                    <input type="radio" class="btn-check" name="companyFilter" id="filterAll" value=""
                           v-model="filter" @change="fetchCompanies(1)">
                    <label class="btn btn-outline-dark btn-sm" for="filterAll">All</label>

                    <input type="radio" class="btn-check" name="companyFilter" id="filterDuplicates" value="duplicates"
                           v-model="filter" @change="fetchCompanies(1)">
                    <label class="btn btn-outline-dark btn-sm" for="filterDuplicates">Duplicates</label>

                    <input type="radio" class="btn-check" name="companyFilter" id="filterUnique" value="unique"
                           v-model="filter" @change="fetchCompanies(1)">
                    <label class="btn btn-outline-dark btn-sm" for="filterUnique">Unique</label>
                </div>

                <small class="text-muted ms-2">Total: {{ totalCount }}</small>
            </div>

            <div>
                <input type="file" ref="fileInput" accept=".csv"
                       class="form-control form-control-sm d-inline-block w-auto me-2" @change="handleFileChange"/>

                <button @click="importCompanies" class="btn btn-danger btn-sm me-2"
                        :disabled="!selectedFile || importProcessing" title="Import company data from CSV file">
                    <span v-if="importProcessing" class="spinner-border spinner-border-sm me-2"></span>
                    Import
                </button>

                <button @click="exportCompanies" class="btn btn-success btn-sm"
                        :disabled="exportProcessing" title="Export company data in CSV file">
                    <span v-if="exportProcessing" class="spinner-border spinner-border-sm me-2"></span>
                    Export
                </button>
            </div>
        </div>

        <div v-if="loadingData" class="loading-overlay text-center p-4">
            <div class="spinner-border text-success" role="status"></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" v-if="!loadingData">
                <thead class="table-light">
                <tr>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Duplicate</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="company in companies" :key="company.id">
                    <td>{{ company.company_name }}</td>
                    <td>{{ company.email }}</td>
                    <td>{{ company.phone_number }}</td>
                    <td>{{ company.is_duplicate ? 'Yes' : 'No' }}</td>
                </tr>
                </tbody>
            </table>

            <nav v-if="!loadingData && pagination.last_page > 1">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                        <button class="page-link" @click="fetchCompanies(1)">First</button>
                    </li>

                    <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                        <button class="page-link" @click="fetchCompanies(pagination.current_page - 1)">Prev</button>
                    </li>

                    <li v-for="page in pagesToShow" :key="page" class="page-item"
                        :class="{ active: page === pagination.current_page }">
                        <button class="page-link" @click="fetchCompanies(page)">{{ page }}</button>
                    </li>

                    <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                        <button class="page-link" @click="fetchCompanies(pagination.current_page + 1)">Next</button>
                    </li>

                    <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                        <button class="page-link" @click="fetchCompanies(pagination.last_page)">Last</button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<style scoped>
.pagination .page-item.active .page-link {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.pagination .page-link {
    color: #198754;
}

.pagination .page-link:hover {
    color: #146c43;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
}
</style>
