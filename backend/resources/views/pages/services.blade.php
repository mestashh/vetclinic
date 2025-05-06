@extends('layouts.app')
@section('title','Услуги')

@section('content')
    <div id="services-app" class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Услуги</h1>
        <div class="overflow-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Название</th>
                    <th class="px-4 py-2">Описание</th>
                    <th class="px-4 py-2">Цена</th>
                    <th class="px-4 py-2">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="adding">
                    <td class="border px-4 py-2">–</td>
                    <td class="border px-4 py-2"><input v-model="newItem.name" class="w-full border p-1"></td>
                    <td class="border px-4 py-2"><input v-model="newItem.description" class="w-full border p-1"></td>
                    <td class="border px-4 py-2"><input type="number" v-model="newItem.price" class="w-full border p-1"></td>
                    <td class="border px-4 py-2 space-x-1">
                        <button @click="saveNew" class="bg-green-500 text-white px-2 py-1 rounded">OK</button>
                        <button @click="cancelAdd" class="bg-gray-400 text-white px-2 py-1 rounded">✕</button>
                    </td>
                </tr>
                <tr v-for="item in items" :key="item.id" class="border-t">
                    <td class="px-4 py-2">@{{ item.id }}</td>
                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <input v-model="item.name" class="w-full border p-1">
                    </td>
                    <td class="px-4 py-2" v-else>@{{ item.name }}</td>

                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <input v-model="item.description" class="w-full border p-1">
                    </td>
                    <td class="px-4 py-2" v-else>@{{ item.description }}</td>

                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <input type="number" v-model="item.price" class="w-full border p-1">
                    </td>
                    <td class="px-4 py-2" v-else>@{{ item.price }}₽</td>

                    <td class="px-4 py-2 space-x-1">
                        <template v-if="editingId===item.id">
                            <button @click="updateItem(item)" class="bg-blue-500 text-white px-2 py-1 rounded">OK</button>
                            <button @click="cancelEdit" class="bg-gray-400 text-white px-2 py-1 rounded">✕</button>
                        </template>
                        <template v-else>
                            <button @click="startEdit(item)" class="bg-yellow-500 text-white px-2 py-1 rounded">✎</button>
                            <button @click="deleteItem(item)" class="bg-red-500 text-white px-2 py-1 rounded">🗑</button>
                        </template>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <button v-if="!adding" @click="startAdd" class="bg-green-600 text-white px-4 py-2 rounded">
                Добавить услугу
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            const { createApp } = Vue;
            createApp({
                data(){ return {
                    items:@json($services),
                    adding:false, editingId:null,
                    newItem:{name:'',description:'',price:''},
                    backup:null
                }},
                methods:{
                    csrf(){axios.defaults.headers.common['X-CSRF-TOKEN']=document.querySelector('meta[name=csrf-token]').content},
                    startAdd(){this.adding=true},
                    cancelAdd(){this.newItem={name:'',description:'',price:''};this.adding=false},
                    async saveNew(){this.csrf();let{data}=await axios.post('{{ route("services.store") }}',this.newItem);this.items.push(data);this.cancelAdd()},
                    startEdit(item){this.editingId=item.id;this.backup=JSON.parse(JSON.stringify(item))},
                    cancelEdit(){let i=this.items.findIndex(x=>x.id===this.editingId);this.items.splice(i,1,this.backup);this.editingId=null},
                    async updateItem(item){this.csrf();await axios.put(`/services/${item.id}`,item);this.editingId=null},
                    async deleteItem(item){if(!confirm('Удалить услугу?'))return;this.csrf();await axios.delete(`/services/${item.id}`);this.items=this.items.filter(x=>x.id!==item.id)}
                }
            }).mount('#services-app')
        </script>
    @endpush
@endsection
