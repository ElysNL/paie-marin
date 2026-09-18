import { reactive } from 'vue';

const state = reactive({
    toasts: [],
});

const timers = new Map();
let nextId = 1;

function add(message, type = 'success', timeout = 4000) {
    const id = nextId++;
    state.toasts.push({ id, message, type });
    if (timeout > 0) {
        const timer = setTimeout(() => remove(id), timeout);
        timers.set(id, timer);
    }
    return id;
}

function remove(id) {
    const index = state.toasts.findIndex(t => t.id === id);
    if (index !== -1) state.toasts.splice(index, 1);
    if (timers.has(id)) {
        clearTimeout(timers.get(id));
        timers.delete(id);
    }
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