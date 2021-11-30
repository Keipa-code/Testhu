import React, {FC, useEffect, useState} from 'react';
import {Button, Col, Container, Form, FormControl, InputGroup, Row} from "react-bootstrap";
import {observer} from "mobx-react-lite";
import {useRootStore} from "../../RootStateContext";
import {storage} from "../../utils/tools";
import QuestionFormList from "../../components/Question/QuestionFormList";
import TagsForm from "../../components/TagsForm/TagsForm";


const NewTest: FC = observer(() => {
    const {newTestStore} = useRootStore()
    const [showDescription, setShowDescription] = useState(false)
    const [showRules, setShowRules] = useState(false)
    const [showWrongAnswers, setShowWrongAnswers] = useState(false)
    const [resultIsPublic, setResultIsPublic] = useState(false)

    useEffect(() => {
        newTestStore.getFromStorage('newTest')
        const interval = setInterval(() => newTestStore.setToStorage('newTest'), 360000)
        return () => clearInterval(interval)
    }, [])

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        newTestStore.inputChange(e.target.value, e.target.name)
    };

    const setStorage = () => {
        storage.set('test', {hour: 10, minute: 50})
    };

    const getFromStorage = () => {
        console.log(storage.get('newTest'))
    };

    return (
        <Container>
            <Row>
                <Col className="col-sm-8">
                    <h2 className="mb-5">Создать новый тест</h2>
                    <Form className="mb-3">
                        <Form.Group className="mb-3">
                            <Form.Label>Название теста</Form.Label>
                            <Form.Control
                                name="testName"
                                placeholder="Введите название"
                                value={newTestStore.test.testName}
                                onChange={handleChange}
                            />
                        </Form.Group>
                        <TagsForm />
                        <Form.Group hidden={!showDescription} className="mb-3">
                            <Form.Label>Описание теста</Form.Label>
                            <Form.Control
                                as="textarea"
                                rows={4}
                                name="description"
                                placeholder="Введите описание"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.description}
                                onChange={handleChange}
                            />
                        </Form.Group>
                        <Button
                            className="mb-3"
                            variant={showDescription ? "danger" : "success"}
                            onClick={() => {setShowDescription(!showDescription)}}
                        >
                            {showDescription ? "Убрать описание" : "Добавить описание"}
                        </Button>
                        <Form.Group hidden={!showRules} className="mb-3">
                            <Form.Label>Правила теста</Form.Label>
                            <Form.Control
                                as="textarea"
                                rows={4}
                                name="rules"
                                placeholder="Введите правила теста"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.rules}
                                onChange={handleChange}
                            />
                        </Form.Group>
                        <br/>
                        <Button
                            className="mb-3"
                            variant={showRules ? "danger" : "success"}
                            onClick={() => {setShowRules(!showRules)}}
                        >
                            {showRules ? "Убрать правила" : "Добавить правила"}
                        </Button>
                    </Form>
                    <Form>
                        <Form.Label>Ограничение по времени</Form.Label>
                        <Row className="align-items-center">
                            <Col xs="4">
                                <InputGroup className="mb-3">
                                    <FormControl
                                        name="hour"
                                        type="number"
                                        value={newTestStore.test.timeLimit.hour}
                                        placeholder="часы"
                                        onChange={handleChange}
                                    />
                                    <InputGroup.Text>ч.</InputGroup.Text>
                                    <FormControl
                                        name="minute"
                                        type="number"
                                        value={newTestStore.test.timeLimit.minute}
                                        placeholder="минуты"
                                        onChange={handleChange}
                                    />
                                    <InputGroup.Text>м.</InputGroup.Text>
                                </InputGroup>
                            </Col>
                        </Row>
                    </Form>

                    <Form>
                        <Form.Check
                            className="mt-3"
                            label={'Разрешить смотреть список неправильных ответов после теста'}
                            onChange={() => {
                                setShowWrongAnswers(!showWrongAnswers)
                            }}
                        />
                        <Form.Check
                            className="mb-5"
                            label={'Сделать все результаты прохождения публичными'}
                            onChange={() => {
                                setResultIsPublic(!resultIsPublic)
                            }}
                        />
                    </Form>
                    <QuestionFormList />
                </Col>
                <Col className="col-sm-4">
                </Col>
            </Row>
            <Row>
                <Col className="mb-5" md={{span: 3, offset: 9}}>
                    <Button>Сохранить</Button>
                </Col>
            </Row>
        </Container>
    )
});

export default NewTest;
