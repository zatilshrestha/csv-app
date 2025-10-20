<script setup>
import {ref, onMounted} from 'vue';
import axios from '../axios';
import {createToaster} from "@meforma/vue-toaster";

const toaster = createToaster({position: 'bottom-right'});

const companies = ref([]);
const selectedFile = ref(null);
const loadingData = ref(false);
const exportProcessing = ref(false);
const importProcessing = ref(false);

onMounted(fetchCompanies);

function handleFileChange(event) {
    selectedFile.value = event.target.files[0];
}

async function fetchCompanies() {
    loadingData.value = true;

    try {
        const response = await axios.get('/companies');
        companies.value = response.data.data;
    } catch (err) {
        toaster.error('Failed to fetch companies');
    } finally {
        loadingData.value = false;
    }
}

async function importCompanies() {
    if (!selectedFile.value) {
        toaster.error('Please select a CSV file first.');
        return;
    }

    importProcessing.value = true;

    const formData = new FormData();
    formData.append('file', selectedFile.value);

    try {
        await axios.post('/companies/import', formData, {
            headers: {'Content-Type': 'multipart/form-data'},
        })

        toaster.success('Company data imported successfully.');
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
            toaster.success('Export successful.');
        })
        .catch(() => {
            exportProcessing.value = false;
            toaster.error('Export failed.');
        });
}
</script>

<template>
    <div class="container p-4">
        <div class="d-flex justify-content-between mb-2">
            <h3>Company List</h3>

            <div>
                <input
                    type="file"
                    ref="fileInput"
                    @change="handleFileChange"
                    accept=".csv"
                    class="form-control form-control-sm d-inline-block w-auto me-2"
                />

                <button
                    @click="importCompanies"
                    class="btn btn-danger btn-sm me-2"
                    :disabled="!selectedFile || importProcessing"
                    title="Import company data from CSV file"
                >
                    <span v-if="importProcessing" class="spinner-border spinner-border-sm me-2"></span>
                    Import
                </button>

                <button
                    @click="exportCompanies"
                    class="btn btn-success btn-sm"
                    :disabled="exportProcessing"
                    title="Export company data in CSV file"
                >
                    <span v-if="exportProcessing" class="spinner-border spinner-border-sm me-2"></span>
                    Export
                </button>
            </div>
        </div>

        <div v-if="loadingData" class="loading-overlay text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>

        <table class="table" v-if="!loadingData">
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
    </div>
</template>

<style scoped>
.container {
    box-shadow: 0 6px 15px 0 rgba(36, 37, 38, 0.08);
}
</style>
