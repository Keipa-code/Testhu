import React, {FC, useEffect, useState} from 'react';
import {AnswerType, IAnswer, IQuestion, QuestionFormStore} from "./QuestionFormStore";
import {Button, Col, Form, Row} from "react-bootstrap";
import {useRootStore} from "../../RootStateContext";
import {observer} from "mobx-react-lite";

type AnswerFormProps = {
    qKey?: number,
}

const AnswerForm: FC<AnswerFormProps> = observer(({qKey}) => {
    const {questionFormStore} = useRootStore()
    const [answer, setAnswer] = useState('')

    function handleAddAnswer(e: React.MouseEvent<HTMLInputElement>) {
        e.preventDefault()
        questionFormStore.addAnswer(qKey, answer)
    }

    function handleChange(e: React.ChangeEvent<HTMLInputElement>) {
        setAnswer(e.target.value)
    }

    return (
        <div>
            <Row className="align-items-center">
                <Col xs="auto">
                    <Form>
                        <Form.Group className="mb-3">
                            <Form.Label>Вариант ответа</Form.Label>
                            <Form.Control placeholder="Введите ответ" onChange={handleChange}/>
                            <Form.Text className="text-muted">
                                В случае, если добавлен только один вариант ответа,
                                то при прохождении теста ответ пишут вручную
                            </Form.Text>
                        </Form.Group>
                    </Form>
                </Col>
                <Col xs="auto">
                    <Button variant="primary" type="submit" onClick={handleAddAnswer}>
                        Submit
                    </Button>
                </Col>
            </Row>
            {questionFormStore.questions[qKey].answers.map((answer, aKey) =>
                <Row key={aKey} className="align-items-center">
                    <Col xs="auto">
                        <Form.Check
                            type="checkbox"
                            checked={answer.correct}
                            className="mb-2"
                            onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                questionFormStore.answerCheckedChange(qKey, aKey, !e.target.checked)
                            }}
                        />
                    </Col>
                    <Col xs="auto">
                        <p>{answer.text}</p>
                    </Col>
                    <Col xs="auto">
                        <button
                            onClick={() => {
                                questionFormStore.removeAnswer(qKey, aKey)
                            }}
                            type="button"
                            className="btn btn-default btn-lg"
                        >
                            <span color="red" className="glyphicon glyphicon-remove" aria-hidden="true"/>
                        </button>
                    </Col>
                </Row>
            )}
        </div>
    )
})

export default AnswerForm;