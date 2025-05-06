@extends('layouts.app')
@section('title','Приёмы')

@section('content')
    <div id="appointments-app" class="p-4">
        <h1 class="text-2xl font-semibold mb-4">Приёмы</h1>
        <div class="overflow-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Дата/время</th>
                    <th class="px-4 py-2">Клиент</th>
                    <th class="px-4 py-2">Питомец</th>
                    <th class="px-4 py-2">Ветеринар</th>
                    <th class="px-4 py-2">Услуги</th>
                    <th class="px-4 py-2">Статус</th>
                    <th class="px-4 py-2">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="adding">
                    <td class="border px-4 py-2">–</td>
                    <td class="border px-4 py-2"><input type="datetime-local" v-model="newItem.scheduled_at" class="w-full border p-1"></td>
                    <td class="border px-4 py-2">
                        <select v-model="newItem.client_id" class="w-full border p-1">
                            <option v-for="c in clients" :value="c.id">@{{ c.first_name }} @{{ c.last_name }}</option>
                        </select>
                    </td>
                    <td class="border px-4 py-2">
                        <select v-model="newItem.pet_id" class="w-full border p-1">
                            <option v-for="p in pets" :value="p.id">@{{ p.name }}</option>
                        </select>
                    </td>
                    <td class="border px-4 py-2">
                        <select v-model="newItem.veterinarian_id" class="w-full border p-1">
                            <option v-for="v in vets" :value="v.id">@{{ v.first_name }} @{{ v.last_name }}</option>
                        </select>
                    </td>
                    <td class="border px-4 py-2">
                        <select multiple v-model="newItem.services" class="w-full border p-1">
                            <option v-for="s in services" :value="s.id">@{{ s.name }}</option>
                        </select>
                    </td>
                    <td class="border px-4 py-2">
                        <select v-model="newItem.status" class="w-full border p-1">
                            <option>scheduled</option><option>done</option><option>canceled</option>
                        </select>
                    </td>
                    <td class="border px-4 py-2 space-x-1">
                        <button @click="saveNew" class="bg-green-500 text-white px-2 py-1 rounded">OK</button>
                        <button @click="cancelAdd" class="bg-gray-400 text-white px-2 py-1 rounded">✕</button>
                    </td>
                </tr>
                <tr v-for="item in items" :key="item.id" class="border-t">
                    <td class="px-4 py-2">@{{ item.id }}</td>
                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <input type="datetime-local" v-model="item.scheduled_at" class="w-full border p-1">
                    </td>
                    <td class="px-4 py-2" v-else>@{{ new Date(item.scheduled_at).toLocaleString() }}</td>
                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <select v-model="item.client_id" class="w-full border p-1">
                            <option v-for="c in clients" :value="c.id">@{{ c.first_name }} @{{ c.last_name }}</option>
                        </select>
                    </td>
                    <td class="px-4 py-2" v-else>@{{ item.client.first_name }} @{{ item.client.last_name }}</td>
                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <select v-model="item.pet_id" class="w-full border p-1">
                            <option v-for="p in pets" :value="p.id">@{{ p.name }}</option>
                        </select>
                    </td>
                    <td class="px-4 py-2" v-else>@{{ item.veterinarian.first_name }} @{{ item.veterinarian.last_name }}</td>
                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <select multiple v-model="item.services" class="w-full border p-1">
                            <option v-for="s in services" :value="s.id">@{{ s.name }}</option>
                        </select>
                    </td>
                    <td class="px-4 py-2" v-else>@{{ item.services.map(s => s.name).join(', ') }}</td>
                    <td class="px-4 py-2" v-if="editingId===item.id">
                        <select v-model="item.status" class="w-full border p-1">
                            <option>scheduled</option><option>done</option><option>canceled</option>
                        </select>
                    </td>
                    <td class="px-4 py-2" v-else>@{{ item.status }}</td>
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
            <button v-if="!adding" @click="startAdd" class="bg-green-600 text-white px-4 py-2 rounded">Добавить приём</button>
        </div>
    </div>

    @push('scripts')
        <script>
            const { createApp } = Vue;
            createApp({
                data(){ return {
                    items: @json($appointments),
                    clients: @json(\App\Models\Client::all()),
                    pets: @json(\App\Models\Pet::all()),
                    vets: @json(\App\Models\Veterinarian::all()),
                    services: @json(\App\Models\Service::all()),
                    adding:false, editingId:null,
                    newItem:{scheduled_at:'', client_id:null, pet_id:null, veterinarian_id:null, services:[], status:'scheduled'},
                    backup:null
                }},
                methods:{
                    csrf(){ axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name=csrf-token]').content },
                    startAdd(){ this.adding=true },
                    cancelAdd(){ this.newItem={scheduled_at:'',client_id:null,pet_id:null,veterinarian_id:null,services:[],status:'scheduled'}; this.adding=false },
                    async saveNew(){ this.csrf(); let {data}=await axios.post('{{ route("appointments.store") }}', this.newItem); this.items.push(data); this.cancelAdd() },
                    startEdit(item){ this.editingId=item.id; this.backup=JSON.parse(JSON.stringify(item)) },
                    cancelEdit(){ let i=this.items.findIndex(x=>x.id===this.editingId); this.items.splice(i,1,this.backup); this.editingId=null },
                    async updateItem(item){ this.csrf(); await axios.put(`/appointments/${item.id}`, item); this.editingId=null },
                    async deleteItem(item){ if(!confirm('Удалить приём?')) return; this.csrf(); await axios.delete(`/appointments/${item.id}`); this.items=this.items.filter(x=>x.id!==item.id) }
                }
            }).mount('#appointments-app')
        </script>
    @endpush
@endsection
