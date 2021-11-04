import React, {useState} from 'react';
import {token} from "../utils/GetJWT";
import axios from "axios";
import {ITest} from "../types/types";
import { Redirect } from 'react-router-dom';
import TokenStore from "../stores/TokenStore";

const CreateTest = () => {
    const [testId, setTestId] = useState<number>()

    async function fetchTestID() {
        try {
            $http.post<ITest>('/api/tests')
                .then((res) => {
                    setTestId(res.data.id)
                })
        } catch (e) {
            alert(e)
        }
    }
    return (
        <div>
            <Redirect to={"/new/" + testId} />
        </div>
    );
};

export default CreateTest;