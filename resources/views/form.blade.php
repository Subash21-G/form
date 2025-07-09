<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Google Form Style (Tailwind)</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    :root {
      --navy: #001f4d;
    }
    .bubble-dropdown {
      position: fixed;
      top: 80px;
      right: 20px;
      width: 90vw;
      max-width: 350px;
      max-height: 80vh;
      overflow-y: auto;
      z-index: 50;
      animation: bubbleFlyIn 0.4s ease-out forwards;
    }
    @keyframes bubbleFlyIn {
      0% { transform: translateX(50%) scale(0.5); opacity: 0; }
      100% { transform: translateX(0) scale(1); opacity: 1; }
    }
  </style>
</head>
<body class="bg-[#0a192f] min-h-screen p-4 text-black" x-data="formApp()">

<div class="max-w-4xl mx-auto">
  <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="h-1.5 bg-[#001f4d]"></div>
    <div class="p-4">

      <!-- Header + Add Button -->
      <div class="flex flex-wrap sm:flex-nowrap justify-between items-end gap-4 mb-4">
        <div class="flex-1 min-w-0">
          <input x-model="formTitle" class="w-full text-2xl font-bold border-b border-gray-300 focus:outline-none mb-2" placeholder="Untitled Form">
          <input x-model="formDescription" class="w-full text-gray-600 border-b border-gray-200 focus:outline-none" placeholder="Form description">
        </div>
        <div x-data="{ showMenu: false }" class="shrink-0">
          <button @click="showMenu = !showMenu" class="bg-[#001f4d] text-white px-4 py-2 rounded-full">➕ Add</button>
          <template x-if="showMenu">
            <div class="bubble-dropdown bg-white rounded shadow-lg p-4">
              <h2 class="text-lg font-semibold mb-2">Choose Field Type</h2>
              <ul class="space-y-2">
                <template x-for="type in [
                  {value:'text',label:'📝 Short Answer'},
                  {value:'number',label:'🔢 Number'},
                  {value:'email',label:'📧 Email'},
                  {value:'textarea',label:'📄 Paragraph'},
                  {value:'checkbox',label:'✅ Multiple Choice'},
                  {value:'radio',label:'🔘 Single Choice'},
                  {value:'select',label:'🕽️ Dropdown'},
                  {value:'file',label:'📁 File Upload'},
                  {value:'location',label:'📍 Location'}
                ]">
                  <li class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded cursor-pointer" @click.prevent="addQuestion(type.value, type.label); showMenu = false">
                    <span x-text="type.label"></span>
                  </li>
                </template>
              </ul>
              <button class="mt-3 w-full text-center py-2 bg-gray-200 hover:bg-gray-300 rounded" @click="showMenu = false">Close</button>
            </div>
          </template>
        </div>
      </div>

      <!-- Builder -->
      <form class="space-y-6" @submit="submitForm">
        <template x-for="(question, index) in questions" :key="question.id">
          <div class="relative bg-gray-100 border border-[#001f4d] rounded p-4">
            <button @click="deleteQuestion(index)" type="button" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">✖</button>
            <input x-model="question.title" class="w-full font-semibold border-b border-gray-300 mb-2" placeholder="Question title">
            <select x-model="question.type" class="w-full text-sm border mb-3">
              <option value="text">📝 Short Answer</option>
              <option value="number">🔢 Number</option>
              <option value="email">📧 Email</option>
              <option value="textarea">📄 Paragraph</option>
              <option value="checkbox">✅ Multiple Choice</option>
              <option value="radio">🔘 Single Choice</option>
              <option value="select">🕽️ Dropdown</option>
              <option value="file">📁 File Upload</option>
              <option value="location">📍 Location</option>
            </select>
            <template x-if="['text','number','email'].includes(question.type)"><input :type="question.type" class="w-full border p-2" :placeholder="question.placeholder" x-model="question.answer"></template>
            <template x-if="question.type === 'textarea'"><textarea class="w-full border p-2" rows="3" :placeholder="question.placeholder" x-model="question.answer"></textarea></template>
            <template x-if="['checkbox','radio','select'].includes(question.type)">
              <div class="space-y-2">
                <template x-for="(option, i) in question.options" :key="i">
                  <div class="flex items-center gap-2">
                    <input :type="question.type === 'select' ? 'text' : question.type" disabled class="form-check-input">
                    <input x-model="question.options[i]" type="text" class="flex-1 border p-1 text-sm" placeholder="Option">
                    <button type="button" @click="question.options.splice(i, 1)" class="text-red-600 hover:underline text-sm">✖</button>
                  </div>
                </template>
                <button type="button" class="text-[#001f4d] text-sm hover:underline" @click="question.options.push('New Option')">➕ Add Option</button>
              </div>
            </template>
            <template x-if="question.type === 'file'"><input type="file" class="w-full border"></template>
            <template x-if="question.type === 'location'">
              <div>
                <div class="flex gap-2">
                  <input type="text" class="flex-1 border p-2" placeholder="Enter location" x-model="question.locationText">
                  <button class="px-3 bg-gray-300 hover:bg-gray-400 rounded" type="button" @click="getLocationAddress(question)">📍</button>
                </div>
                <template x-if="question.locationAddress">
                  <p class="text-xs text-gray-600 mt-1">Detected: <span x-text="question.locationAddress"></span></p>
                </template>
              </div>
            </template>
          </div>
        </template>
        <div class="text-end">
          <button type="submit" class="bg-[#001f4d] text-white px-6 py-2 rounded">Submit</button>
        </div>
      </form>

      <!-- Preview & URL Output -->
      <div class="mt-10 pt-5 border-t">
        <h2 class="text-lg font-semibold" x-text="formTitle"></h2>
        <p class="text-sm text-gray-600 mb-4" x-text="formDescription"></p>
        <template x-for="question in questions" :key="question.id">
          <div class="mb-5">
            <label class="font-semibold" x-text="question.title"></label>
            <template x-if="['text','number','email'].includes(question.type)"><input :type="question.type" class="w-full border p-2" :value="question.answer" readonly></template>
            <template x-if="question.type === 'textarea'"><textarea class="w-full border p-2" rows="3" :value="question.answer" readonly></textarea></template>
            <template x-if="question.type === 'checkbox'"><template x-for="(option, i) in question.options" :key="i"><div class="flex items-center gap-2"><input type="checkbox" class="form-check-input" :id="'check-' + question.id + '-' + i"><label class="text-sm" :for="'check-' + question.id + '-' + i" x-text="option"></label></div></template></template>
            <template x-if="question.type === 'radio'"><template x-for="(option, i) in question.options" :key="i"><div class="flex items-center gap-2"><input type="radio" :name="'radio-' + question.id" class="form-check-input" :id="'radio-' + question.id + '-' + i"><label class="text-sm" :for="'radio-' + question.id + '-' + i" x-text="option"></label></div></template></template>
            <template x-if="question.type === 'select'"><select class="w-full border p-2"><template x-for="(option, i) in question.options" :key="i"><option x-text="option"></option></template></select></template>
            <template x-if="question.type === 'file'"><input type="file" class="w-full border"></template>
            <template x-if="question.type === 'location'"><input type="text" class="w-full border p-2" :value="question.locationAddress || question.locationText" readonly></template>
          </div>
        </template>

        <template x-if="generatedUrl">
          <div class="mt-6 bg-gray-100 p-4 rounded border border-[#001f4d]">
            <h3 class="font-bold mb-2 text-[#001f4d]">Generated URL</h3>
            <p class="text-sm break-words text-blue-700 underline">
              <a :href="generatedUrl" target="_blank" x-text="generatedUrl"></a>
            </p>
            <div class="mt-4 flex gap-2">
              <a :href="`https://wa.me/?text=${encodeURIComponent(generatedUrl)}`" target="_blank" class="text-green-600 hover:underline">WhatsApp</a>
              <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(generatedUrl)}`" target="_blank" class="text-blue-600 hover:underline">Facebook</a>
              <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(generatedUrl)}`" target="_blank" class="text-sky-600 hover:underline">Twitter</a>
              <a :href="`https://www.instagram.com/`" target="_blank" class="text-pink-500 hover:underline">Instagram</a>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</div>

<script>
function formApp() {
  return {
    formTitle: 'Untitled Form',
    formDescription: 'Form description',
    questions: [],
    generatedUrl: '',
    addQuestion(type = 'text', title = 'Untitled', options = []) {
      this.questions.push({
        id: Date.now() + Math.random(),
        type,
        title,
        placeholder: title,
        answer: '',
        options: ['checkbox', 'radio', 'select'].includes(type) ? options : [],
        locationText: '',
        locationAddress: ''
      });
    },
    deleteQuestion(index) {
      this.questions.splice(index, 1);
    },
    getLocationAddress(question) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            question.locationText = `${lat}, ${lon}`;
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
              .then(res => res.json())
              .then(data => {
                question.locationAddress = data.display_name;
              });
          },
          () => alert('Unable to retrieve location')
        );
      } else {
        alert('Geolocation not supported');
      }
    },
    submitForm(event) {
      event.preventDefault();
      const formData = {
        title: this.formTitle,
        description: this.formDescription,
        questions: this.questions
      };
      const query = encodeURIComponent(JSON.stringify(formData));
      this.generatedUrl = `${location.origin}${location.pathname}?data=${query}`;
    }
  };
}
</script>

</body>
</html>
