import React, {FC, useEffect, useState} from 'react';
import {Button, Col, Container, Form, FormCheck, FormControl, InputGroup, Row} from "react-bootstrap";
import {useParams} from 'react-router-dom';
import {ITest} from "../../types/types";
import {observer, useObserver} from "mobx-react-lite";
import {useRootStore} from "../../RootStateContext";
import {Observer} from "mobx-react-lite/dist/ObserverComponent";

interface NewTestParams {
    id: string;
}

export interface token {
    token: string;
}

interface Props {}

const NewTest: FC = observer(() => {
    const { newTestStore } = useRootStore();
    const [test, setTest] = useState({
        testName: '321'
    })

    let testName = '321'
    const params = useParams<NewTestParams>()
    useEffect(() => {
        newTestStore.getDetail('1')

    },[])

    useEffect(() => {
        setTest({...test, testName: newTestStore.test.testName})
    },[newTestStore.test])

    /*async function fetchTest() {
        try {
            $http.get('/api/tests/171').then((response: any) => {setTest(response.data)})
        } catch (e) {
            alert(e)
        }
    }*/

    function fetchTest(){
        //newTestStore.getDetail('1')
        console.log(newTestStore.test.tags['0'].tagName)
    }

    function button2(){
        testName = '123'
    }

    function handleChangeName(e: React.ChangeEvent<HTMLInputElement>){
        setTest({testName: e.target.value})
        newTestStore.test.testName = e.target.value
    }


    return (
        <Container>
            <Row>
                <Col className="col-sm-8">
                    <h2>Создать новый тест</h2>
                    <FormControl
                        className="mt-3"
                        placeholder={"Название " + testName}
                        aria-label={"Название " + testName}
                        aria-describedby="basic-addon2"
                        value={newTestStore.test.testName}
                        onChange={ handleChangeName }
                    />
                    <FormControl
                        className="mt-3"
                        placeholder="Тэги"
                        aria-label="Тэги"
                        aria-describedby="basic-addon2"
                    />
                    <Button className="mt-3" onClick={fetchTest}>Добавить предисловие</Button>
                    <Button className="ms-3 mt-3" onClick={button2}>Fetch</Button>
                    <Form>
                        <Form.Check className="mt-3"
                                    label={'Разрешить смотреть список неправильных ответов после теста'}/>
                        <Form.Check label={'Сделать все результаты прохождения публичными'}/>
                    </Form>
                    <h2 className="mt-4">Вопросы теста</h2>

                </Col>
                <Col className="col-sm-4">
                </Col>
            </Row>
            <Row>
                <Col className="mt-5" md={{ span: 3, offset: 9 }}>
                    <Button>Сохранить</Button>
                </Col>
            </Row>
        </Container>
    )
});

export default NewTest;
