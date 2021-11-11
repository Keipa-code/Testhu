export const storage = {
    get: (key: string) => {
        const value = localStorage.getItem(key)
        return JSON.parse(<string>value)
    },

    set: (key: string, value: any): void => {
        if (value !== null && value !== undefined) {
            localStorage.setItem(key, JSON.stringify(value))
        }
    },

    remove: (key: string): void => {
        localStorage.removeItem(key)
    },

    clear: (): void => {
        localStorage.clear()
    }
}

export function computeTimeLimit(value) {
    const minute = (value < 60) ? value : (value - (Math.floor(value / 60) * 60))
    const hour = Math.floor(value / 60)
    return {minute, hour}
}