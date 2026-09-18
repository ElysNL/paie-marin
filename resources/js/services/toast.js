import { reactive } from 'vue';

const state = reactive({
    toasts: [],
});

let nextId = 1;

function add(message, type = 'success', timeout = 4000) {
    const id = nextId++;
    state.toasts.push({ id, message, type });
    if (timeout > 0) {
        setTimeout(() => remove(id), timeout);
    }
    return id;
}

function remove(id) {
    const index = state.toasts.findIndex(t => t.id === id);
    if (index !== -1) state.toasts.splice(index, 1);
}

export function useToasts() {
    return {
        toasts: state.toasts,
        success: (message) => add(message, 'success'),
        error: (message) => add(message, 'error', 6000),
        info: (message) => add(message, 'info'),
        remove,
    };
}