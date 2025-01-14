<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Chirp from "@/Components/Chirp.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, Head } from "@inertiajs/vue3";
import axios from "axios";

defineProps(["chirps"]);

const form = useForm({
    message: "",
    topic: "",
    mood: "",
    seriousnessLevel: 1,
});

function test() {
    axios.post(route("chirps.generate")).then((response) => {
        console.log(response);
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form
                @submit.prevent="
                    form.post(route('chirps.store'), {
                        onSuccess: () => form.reset(),
                    })
                "
            >
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                <PrimaryButton class="mt-4">Chirp</PrimaryButton>
            </form>
            <form
                @submit.prevent="
                    form.post(route('chirps.generate'), {
                        onSuccess: () => form.reset(),
                    })
                "
                class="mt-6"
            >
                <h2>Generate AI Chirp</h2>
                <div class="mt-2">
                    <label for="topic" class="block">Topic</label>
                    <input
                        id="topic"
                        v-model="form.topic"
                        type="text"
                        placeholder="Enter a topic"
                        class="block w-full border-gray-300 rounded-md"
                    />
                    <InputError :message="form.errors.topic" class="mt-2" />
                </div>
                <div class="mt-2">
                    <label for="mood" class="block">Mood</label>
                    <input
                        id="mood"
                        v-model="form.mood"
                        type="text"
                        placeholder="Enter a mood"
                        class="block w-full border-gray-300 rounded-md"
                    />
                    <InputError :message="form.errors.mood" class="mt-2" />
                </div>
                <div class="mt-2">
                    <label for="seriousnessLevel" class="block"
                        >Seriousness Level (1-3)</label
                    >
                    <input
                        id="seriousnessLevel"
                        v-model="form.seriousnessLevel"
                        type="number"
                        min="1"
                        max="3"
                        class="block w-full border-gray-300 rounded-md"
                    />
                    <InputError
                        :message="form.errors.seriousnessLevel"
                        class="mt-2"
                    />
                </div>
                <PrimaryButton class="mt-4">Generate Chirps</PrimaryButton>
            </form>
            <PrimaryButton class="mt-4" @click="test">Generate</PrimaryButton>
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <Chirp v-for="chirp in chirps" :key="chirp.id" :chirp="chirp" />
            </div>
            <div v-if="$page.props.flash?.alternatives?.length" class="mt-6">
                <h2>AI-Generated Chirps</h2>
                <ul>
                    <li
                        v-for="(alternative, index) in $page.props.flash
                            .alternatives"
                        :key="index"
                    >
                        {{ alternative }}
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
