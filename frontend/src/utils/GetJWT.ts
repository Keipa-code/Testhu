import React from 'react';
import axios from "axios";

export interface token {
    token: string;
}

export
    async function getToken() {
        try {
            const response = await axios.post<token>(
                '/api/api/login',
                {
                    username: "frontend_anonymous",
                    password: "12345678"
                }, {
                    headers: {'Content-Type': 'application/json'}
                })
            return response.data.token
        } catch (e) {
            alert(e)
        }
    }
