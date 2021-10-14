import React from 'react';
import {token} from "../utils/GetJWT";
import axios from "axios";
import {ITest} from "../types/types";
import { Redirect } from 'react-router-dom';
import TokenStore from "../stores/TokenStore";

const CreateTest = () => {

    async function fetchTestID() {
        try {
            const response = await axios.post<ITest>(
                'http://api/api/tests',
                {date: 'now', auth_bearer: TokenStore.token},
                {headers: {'Content-Type': 'application/json'}}
            )
            return response.data.id
        } catch (e) {
            alert(e)
        }
    }
    const id = fetchTestID();
    return (
        <div>
            <Redirect to={"/new/" + id} />
        </div>
    );
};

export default CreateTest;